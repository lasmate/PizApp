<?php
function renderNavbar($activePage) {
    session_start();
    $isConnected = isset($_SESSION['user_id']);
    $pages = [
        'connexion' => 'Connexion',
        'index' => 'Accueil',
        'inscription' => 'Créer un Compte',
    ];
    echo '<nav>';
    foreach ($pages as $page => $title) {
        $activeClass = ($page === $activePage) ? 'active' : '';
        // Afficher le bouton seulement si connecté OU si ce n'est pas la page d'accueil
        if ($isConnected || $page === 'connexion' || $page === 'inscription') {
            echo "<div class=\"nav-item $activeClass\" onclick=\"location.href='$page.php'\"><span class=\"icon-circle\"></span>$title</div>";
        }
    }
    // Ajouter un bouton de déconnexion si connecté
    if ($isConnected) {
        echo "<div class=\"nav-item\" onclick=\"location.href='Deconnexion.php'\"><span class=\"icon-circle\"></span>Déconnexion</div>";
    }
    echo '</nav>';
}
?>