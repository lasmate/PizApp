<?php
/**
 * commande_valide.php
 * Page de confirmation affichée après validation d'une commande
 */
session_start();
require_once 'ConnexionBDD.php';
include_once 'navbar.php';
renderNavbar('productlist');

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = null;
if ($orderId > 0) {
    $sql = "SELECT idcommande, date_heure_commande, montant_ttc, idetat FROM commande WHERE idcommande = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Commande valide - AppResto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="slide-in-blurred-top">
    <div class="HeroCard">
        <div style="padding:2rem;max-width:800px;margin:0 auto;text-align:center;">
            <h1>Commande validée</h1>
            <?php if ($order): ?>
                <p>Merci — votre commande <strong>#<?php echo htmlspecialchars($order['idcommande']); ?></strong> a bien été enregistrée.</p>
                <p>Montant payé : <strong><?php echo number_format((float)$order['montant_ttc'], 2); ?>€</strong></p>
                <p>Date : <?php echo htmlspecialchars($order['date_heure_commande']); ?></p>
                <a class="primary-btn" href="productlist.php">Retour à l'accueil</a>
            <?php else: ?>
                <p>Commande introuvable.</p>
                <a class="primary-btn" href="productlist.php">Retour à l'accueil</a>
            <?php endif; ?>
        </div>
    </div>
    <script src="scripts/main.js"></script>
    <script src="scripts/form-helpers.js"></script>
</body>
</html>
