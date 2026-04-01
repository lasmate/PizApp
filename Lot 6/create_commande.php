<?php
/**
 * create_commande.php
 * API serveur pour créer une commande à partir d'un panier (JSON POST).
 * Retourne JSON { success: bool, idcommande: int }
 */

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
$ids = array_values(array_unique($ids));// check anti duplicata d'id

if (count($ids) === 0) {
    echo json_encode(['success' => false, 'message' => 'Panier vide']);
    exit();
}

// Construit une liste d'entiers sûre pour IN() - entiers uniquement
$safeIds = implode(',', array_map('intval', $ids));
$sql = "SELECT idproduit, prixproduit FROM produit WHERE idproduit IN ($safeIds)";// prepare commande pour fetch les prix
$res = mysqli_query($conn, $sql);
if ($res === false) {
    echo json_encode(['success' => false, 'message' => 'Erreur SQL lors de la récupération des prix: '.mysqli_error($conn)]);
    exit();
}

$priceMap = [];
while ($row = mysqli_fetch_assoc($res)) {
    $priceMap[(int)$row['idproduit']] = (float)$row['prixproduit'];
}

// calcule le total côté PHP (sans surcharge) — utilisé comme contrôle/sanity check
$php_subtotal = 0.0;
foreach ($cart as $it) {
    $pid = (int)$it['id'];
    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 1;
    $price = isset($priceMap[$pid]) ? $priceMap[$pid] : 0.0;
    $php_subtotal += $price * $qty;
}

// applique la surcharge côté PHP comme vérification (les triggers DB sont la source de vérité)
$surcharge_rate = ($type_commande === 1) ? 0.055 : 0.10; // 5.5% pour à emporter (1), 10% pour sur place (0)
$php_expected_total = round($php_subtotal * (1 + $surcharge_rate), 2);

// Insère dans la table `commande`
$userId = (int)$_SESSION['user_id'];
$now = date('Y-m-d H:i:s');
 $idetat = 1; // En préparation
// $type_commande déjà défini depuis l'entrée (0 = sur place, 1 = à emporter)


// Commence une transaction pour garantir l'atomicité
mysqli_begin_transaction($conn);

// Insère la commande avec montant provisoire (les triggers DB recalculeront le montant réel)
$insertCmd = "INSERT INTO commande (date_heure_commande, montant_ttc, type_commande, iduser, idetat) VALUES (?, ?, ?, ?, ?)";
$stmt2 = mysqli_prepare($conn, $insertCmd);
if ($stmt2 === false) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Erreur SQL prepare insert commande']);
    exit();
}
// montant_ttc = 0.00 placeholder; triggers after insert/update will set the real total
$placeholderMontant = 0.00;
mysqli_stmt_bind_param($stmt2, 'sdiii', $now, $placeholderMontant, $type_commande, $userId, $idetat);
$ok = mysqli_stmt_execute($stmt2);
if (!$ok) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Impossible de créer la commande: '.mysqli_error($conn)]);
    exit();
}

$orderId = mysqli_insert_id($conn);

// Insère les lignes de commande (`ligne_de_commande`)
$insertLine = "INSERT INTO ligne_de_commande (idcommande, idproduit, quantite, total_ht) VALUES (?, ?, ?, ?)";
 $stmt3 = mysqli_prepare($conn, $insertLine);
if ($stmt3 === false) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Erreur SQL prepare insert ligne']);
    exit();
}

// Les triggers DB (before_ligne_insert) calculent total_ht à partir du prix en base.
$placeholder_total_ht = 0.00;
foreach ($cart as $it) {
    $pid = (int)$it['id'];
    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 1;
    $price = isset($priceMap[$pid]) ? $priceMap[$pid] : 0.0;
    $total_ht = $price * $qty;
    mysqli_stmt_bind_param($stmt3, 'iiid', $orderId, $pid, $qty, $placeholder_total_ht);
    mysqli_stmt_execute($stmt3);
}

// Commit the transaction so triggers' AFTER INSERT updates are applied
mysqli_commit($conn);

// Récupère le montant calculé par les triggers côté DB
$res2 = mysqli_query($conn, "SELECT montant_ttc FROM commande WHERE idcommande = " . intval($orderId) . " LIMIT 1");
if ($res2 === false) {
    echo json_encode(['success' => true, 'idcommande' => $orderId, 'warning' => 'Commande créée mais impossible de récupérer le total DB: '.mysqli_error($conn)]);
    exit();
}
$row2 = mysqli_fetch_assoc($res2);
$db_total = isset($row2['montant_ttc']) ? round((float)$row2['montant_ttc'], 2) : null;

// Compare PHP-expected total (avec surcharge appliquée) vs valeur calculée par la DB
$note = null;
if ($db_total !== null) {
    $diff = abs($db_total - $php_expected_total);
    if ($diff > 0.05) { // tolérance 5 centimes
        $note = "Divergence entre total attendu côté PHP ({$php_expected_total}€) et total DB ({$db_total}€).";
    }
}

// Retourne l'ID commande et, si nécessaire, la note de divergence
$resp = ['success' => true, 'idcommande' => $orderId];
if ($db_total !== null) $resp['db_total'] = number_format($db_total, 2, '.', '');
if (isset($php_expected_total)) $resp['php_expected_total'] = number_format($php_expected_total, 2, '.', '');
if ($note) $resp['warning'] = $note;

echo json_encode($resp);
exit();
