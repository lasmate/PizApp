<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Connexion.php');
    exit();
}

include_once 'navbar.php';
include_once 'productcard.php';
// include_once 'cart.php';

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
    <div class="HeroCard" style="overflow-y: scroll; overflow-x: hidden;">
        <div class="product-grid-sidebar-wrapper">
               <div class="LandingText">
                   <h1>AppResto</h1>
                   <h3>Votre premier stop pour un creux</h3>
               </div>
               <div class="sidebar-bubble">
                   <button class="sidebar-btn active">Carte</button>
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
        <?php echo renderCart(); ?>
        <!-- New Cart and Checkout toggles at top right edge -->
        <div class="cart-checkout-toggles">
            <button class="cart-toggle-semicircle" aria-label="Ouvrir le panier" title="Ouvrir le panier">🛒</button>
            <button class="checkout-toggle-semicircle" aria-label="Passer à la caisse" title="Passer à la caisse">💳</button>
        </div>


   </div>
    <!-- Scripts -->
    <script src="scripts/main.js"></script>
    <script src="scripts/cart.js"></script>
    <script src="scripts/checkout.js"></script>
    <script src="scripts/product-filters.js"></script>
</body>
</html>