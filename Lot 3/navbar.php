<?php
function renderNavbar($activePage) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $isConnected = isset($_SESSION['user_id']);
    $homeTarget = $isConnected ? 'productlist.php' : 'index.php';

    echo '<nav>';
   

    echo '<div class="nav-links">';

    // Accueil : redirige vers index.php si non connecté, vers productlist.php si connecté
    if (!$isConnected) {
        echo '<div class="nav-item ' . ($activePage === 'index' ? 'active' : '') . '" onclick="location.href=\'index.php\'"><span class="icon-circle"><img src="img/cottage_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt=""></span>Accueil</div>';
    } else {
        echo '<div class="nav-item ' . ($activePage === 'productlist' ? 'active' : '') . '" onclick="location.href=\'productlist.php\'"><span class="icon-circle"><img src="img/cottage_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt=""></span>Accueil</div>';
    }
    
    // Connexion/Inscription visibles si non connecté
    if (!$isConnected) {
        echo '<div class="nav-item ' . ($activePage === 'connexion' ? 'active' : '') . '" onclick="location.href=\'Connexion.php\'"><span class="icon-circle"><img src="img/input_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt=""></span>Connexion</div>';
        echo '<div class="nav-item ' . ($activePage === 'inscription' ? 'active' : '') . '" onclick="location.href=\'CreateAccount.php\'"><span class="icon-circle"><img src="img/group_add_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt=""></span>Inscription</div>';
    }
    // Si connecté, afficher l'état + déconnexion
    if ($isConnected) {
        $login = htmlspecialchars($_SESSION['user_login']);
        echo '<div class="nav-item connected-info"><span class="icon-circle"><img src="img/for_you_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt=""></span><b>' . $login . '</b></div>';
        echo '<div class="nav-item" onclick="location.href=\'Deconnexion.php\'"><span class="icon-circle"><img src="img/input_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt=""></span>Déconnexion</div>';
    }

    echo '</div>';
    echo '<div class="nav-brand" role="link" tabindex="0" onclick="location.href=\'' . $homeTarget . '\'" onkeypress="if(event.key===\'Enter\'){location.href=\'' . $homeTarget . '\';}">';
    echo '<span class="nav-brand-title">AppResto</span>';
    echo '<span class="nav-brand-tagline">Votre premier stop pour un creux</span>';
    echo '</div>';
    echo '</nav>';
}
?>