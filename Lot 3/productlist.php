<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Connexion.php');
    exit();
}

include_once 'navbar.php';
include_once 'productcard.php';

renderNavbar('productlist');

// Get products data
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
    <div class="HeroCard" style="overflow: hidden;">
        <div class="product-grid-sidebar-wrapper">
               <div class="LandingText">
            <h1>AppResto</h1>
            <h3>Votre premier stop pour un creux</h3>
            <div class="sidebar-bubble">
                <button class="sidebar-btn active">Menu</button>
                <button class="sidebar-btn">Pizza</button>
                <button class="sidebar-btn">Boissons</button>
                <button class="sidebar-btn">Desserts</button>
            </div>
                 
        </div>  
            <div class="product-grid">
                <?php
                // Display all products using the productcard component
                foreach ($products as $product) {
                    echo renderProductCard(
                        $product['title'],
                        $product['subhead'],
                        $product['image'],
                        $product['price'],
                        $product['id']
                    );
                }
                ?>
            </div>
        </div>
        <!-- Cart Backdrop and Modal inside HeroCard -->
        <div class="cart-backdrop" aria-hidden="true"></div>
        <div class="cart-modal" role="dialog" aria-labelledby="cart-title" aria-modal="true">
            <div class="cart-modal-header">
                <h3 id="cart-title">Votre panier</h3>
                <button class="cart-close" aria-label="Fermer">✕</button>
            </div>
            <div class="cart-modal-content">
                <div class="cart-item">
                    <span class="item-count">3</span>
                    <span class="item-name">Pizza1</span>
                    <span class="item-price">12.99€</span>
                </div>
                <div class="cart-item">
                    <span class="item-count">2</span>
                    <span class="item-name">Coca Cola</span>
                    <span class="item-price">2.50€</span>
                </div>
                <div class="cart-item">
                    <span class="item-count">1</span>
                    <span class="item-name">Tiramisu</span>
                    <span class="item-price">6.90€</span>
                </div>
            </div>
            <div class="cart-modal-footer">
                <button class="checkout-btn">Passer à la caisse</button>
            </div>
        </div>

        <!-- Small toggle button inside HeroCard (bottom-right) -->
        <button class="cart-toggle" aria-label="Basculer le panier" title="Ouvrir le panier">🛒</button>

   </div>
    <!-- Scripts -->
    <script src="scripts/main.js"></script>
    <script src="scripts/cart.js"></script>
    <script src="scripts/product-filters.js"></script>
</body>
</html>