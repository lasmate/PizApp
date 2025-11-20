<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once 'ConnexionBDD.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié']);
    exit();
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data || !isset($data['cart']) || !is_array($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Données de panier invalides']);
    exit();
}

$cart = $data['cart'];

// Détermine type_commande : 1 = à emporter (par défaut), 0 = sur place
$type_commande = 1;
if (isset($data['type_commande'])) {
    $tc = (int)$data['type_commande'];
    if ($tc === 0 || $tc === 1) {
        $type_commande = $tc;
    }
}

// Calcule le montant total en prenant les prix depuis la base plutôt que ceux fournis côté client

$ids = array_map(function($it){ return (int)$it['id']; }, $cart);
$ids = array_values(array_unique($ids));

if (count($ids) === 0) {
    echo json_encode(['success' => false, 'message' => 'Panier vide']);
    exit();
}

// Construit une liste d'entiers sûre pour IN() - entiers uniquement
$safeIds = implode(',', array_map('intval', $ids));
$sql = "SELECT idproduit, prixproduit FROM produit WHERE idproduit IN ($safeIds)";
$res = mysqli_query($conn, $sql);
if ($res === false) {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL lors de la récupération des prix: '.mysqli_error($conn)]);
    exit();
}

$priceMap = [];
while ($row = mysqli_fetch_assoc($res)) {
    $priceMap[(int)$row['idproduit']] = (float)$row['prixproduit'];
}

// calcule le total
$total = 0.0;
foreach ($cart as $it) {
    $pid = (int)$it['id'];
    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 1;
    $price = isset($priceMap[$pid]) ? $priceMap[$pid] : 0.0;
    $total += $price * $qty;
}

// Insère dans la table `commande`
$userId = (int)$_SESSION['user_id'];
$now = date('Y-m-d H:i:s');
 $idetat = 1; // En préparation
// $type_commande déjà défini depuis l'entrée (0 = sur place, 1 = à emporter)


// Pour éviter des problèmes d'ordre liés aux clés étrangères dans le schéma DB, désactive temporairement les vérifications des clés étrangères
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

$insertCmd = "INSERT INTO commande (date_heure_commande, montant_ttc, type_commande, iduser, idetat) VALUES (?, ?, ?, ?, ?)";
$stmt2 = mysqli_prepare($conn, $insertCmd);
if ($stmt2 === false) {
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
    echo json_encode(['success' => false, 'message' => 'Erreur SQL prepare insert commande']);
    exit();
}
mysqli_stmt_bind_param($stmt2, 'sdiii', $now, $total, $type_commande, $userId, $idetat);
$ok = mysqli_stmt_execute($stmt2);
if (!$ok) {
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
    echo json_encode(['success' => false, 'message' => 'Impossible de créer la commande: '.mysqli_error($conn)]);
    exit();
}

$orderId = mysqli_insert_id($conn);

// Insère les lignes de commande (`ligne_de_commande`)
$insertLine = "INSERT INTO ligne_de_commande (idcommande, idproduit, quantite, total_ht) VALUES (?, ?, ?, ?)";
$stmt3 = mysqli_prepare($conn, $insertLine);
if ($stmt3 === false) {
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
    echo json_encode(['success' => false, 'message' => 'Erreur SQL prepare insert ligne']);
    exit();
}

foreach ($cart as $it) {
    $pid = (int)$it['id'];
    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 1;
    $price = isset($priceMap[$pid]) ? $priceMap[$pid] : 0.0;
    $total_ht = $price * $qty;
    mysqli_stmt_bind_param($stmt3, 'iiid', $orderId, $pid, $qty, $total_ht);
    mysqli_stmt_execute($stmt3);
}

// Réactive les vérifications des clés étrangères
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

echo json_encode(['success' => true, 'idcommande' => $orderId]);
exit();
