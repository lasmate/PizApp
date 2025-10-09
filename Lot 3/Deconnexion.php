<?php
session_start();
session_unset();
session_destroy();
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
    <nav>
        <div class="nav-item" onclick="location.href='connexion.php'"><span class="icon-circle"><img src="img/input_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Connexion</div>
        <div class="nav-item" onclick="location.href='index.php'"><span class="icon-circle"><img src="img/cottage_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Acceuil</div>
        <div class="nav-item" onclick="location.href='CreateAccount.php'"><span class="icon-circle"><img src="img/group_add_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Inscription</div>
    </nav>
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