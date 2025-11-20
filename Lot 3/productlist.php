<?php
/**
 * Page Liste des Produits
 * - Affiche une grille de produits récupérés depuis la base de données
 * - Chaque produit est rendu via le composant productcard.php
 * - Inclut le panneau coulissant du panier depuis cart.php
 */
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: Connexion.php');
    exit();
}

include_once 'navbar.php';
include_once 'productcard.php';
include_once 'products.php';
include_once 'cart.php';

renderNavbar('productlist');


// Récupère les données des produits
$products = getSampleProducts();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des produits - AppResto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="slide-in-blurred-top">
    <div class="HeroCard" style="overflow-y: scroll; overflow-x: hidden;">
        <div class="product-grid-sidebar-wrapper">
               <div class="sidebar-bubble">
                   <button class="sidebar-btn active">Carte</button>
                   <button class="sidebar-btn">Pizza</button>
                   <button class="sidebar-btn">Boissons</button>
                   <button class="sidebar-btn">Desserts</button>
                </div>
        
            <div class="product-grid">
                <?php
                // Affiche tous les produits en utilisant le composant productcard
                foreach ($products as $product) {
                    echo renderProductCard(
                        $product['title'],
                        $product['subhead'],
                        $product['image'],
                        $product['price'],
                        $product['type'],
                        $product['id']
                    );
                }
                ?>
            </div>
        </div>
        
        <?php // Panneau panier coulissant + bascule ?>
        <?php renderCartPanel(); ?>
   </div>
    <!-- Scripts -->
    <script src="scripts/main.js"></script>
    <script src="scripts/cart.js"></script>
    <script src="scripts/product-filters.js"></script>
</body>
</html>