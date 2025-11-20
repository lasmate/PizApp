<?php
/**
 * Deconnexion.php
 * Déconnecte l'utilisateur et affiche une confirmation
 */
session_start();
session_unset();
session_destroy();
include_once 'navbar.php';
renderNavbar('');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion - AppResto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="slide-in-blurred-top">
    <div class="HeroCard">
        <div class="LandingText">
            <h1>AppResto</h1>
            <h3>Vous avez bien été déconnecté.</h3>
        </div>
        <div style="text-align:center;margin-top:2rem">
            <a href="connexion.php" class="primary-btn"><span class="btn-icon">●</span>Se reconnecter</a>
            <a href="index.php" class="primary-btn"><span class="btn-icon">●</span>Retourner sur l'acceuil</a>
        </div>
    </div>
</body>
</html>