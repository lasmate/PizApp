<?php
function renderNavbar($activePage) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $isConnected = isset($_SESSION['user_id']);
    echo '<nav>';
    
    // Accueil : redirige vers index.php si non connecté, vers productlist.php si connecté
    if (!$isConnected) {
        echo '<div class="nav-item ' . ($activePage === 'index' ? 'active' : '') . '" onclick="location.href=\'index.php\'"><span class="icon-circle"><img src="img/cottage_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Accueil</div>';
    } else {
        echo '<div class="nav-item ' . ($activePage === 'productlist' ? 'active' : '') . '" onclick="location.href=\'productlist.php\'"><span class="icon-circle"><img src="img/cottage_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Accueil</div>';
    }
    
    // Connexion/Inscription visibles si non connecté
    if (!$isConnected) {
        echo '<div class="nav-item ' . ($activePage === 'connexion' ? 'active' : '') . '" onclick="location.href=\'Connexion.php\'"><span class="icon-circle"><img src="img/input_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Connexion</div>';
        echo '<div class="nav-item ' . ($activePage === 'inscription' ? 'active' : '') . '" onclick="location.href=\'CreateAccount.php\'"><span class="icon-circle"><img src="img/group_add_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Inscription</div>';
    }
    // Si connecté, afficher l'état + déconnexion
    if ($isConnected) {
        $login = htmlspecialchars($_SESSION['user_login']);
        echo '<div class="nav-item connected-info"><span class="icon-circle"><img src="img/for_you_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span><b>' . $login . '</b></div>';
        echo '<div class="nav-item" onclick="location.href=\'Deconnexion.php\'"><span class="icon-circle"><img src="img/input_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Déconnexion</div>';
    }
    echo '</nav>';
}
?>