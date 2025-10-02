<?php
function renderNavbar($activePage) {
    $pages = [
        'connexion' => 'Connexion',
        'index' => 'Accueil',
        'inscription' => 'Créer un Compte',
    ];
    echo '<nav>';
    foreach ($pages as $page => $title) {
        $activeClass = ($page === $activePage) ? 'active' : '';
        echo "<div class=\"nav-item $activeClass\" onclick=\"location.href='$page.html'\"><span class=\"icon-circle\">" . "</span>$title</div>";
    }
    echo '</nav>';
}
?>