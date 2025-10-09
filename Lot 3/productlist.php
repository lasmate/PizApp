<?php
include_once 'navbar.php';
renderNavbar('productlist');
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
                <div class="product-card">
                    <div class="product-header">
                        <span class="product-title">Pizza1</span>
                        <span class="product-subhead">Subhead</span>
                    </div>
                    <div class="product-image-placeholder">
                        <!-- Placeholder for image -->
                        <span>🍕</span>
                    </div>
                    <button class="add-to-cart">Ajouter Au Panier</button>
                </div>
                <div class="product-card">
                    <div class="product-header">
                        <span class="product-title">Pizza1</span>
                        <span class="product-subhead">Subhead</span>
                    </div>
                    <div class="product-image-placeholder">
                        <!-- Placeholder for image -->
                        <span>🍕</span>
                    </div>
                    <button class="add-to-cart">Ajouter Au Panier</button>
                </div>
                <div class="product-card">
                    <div class="product-header">
                        <span class="product-title">Pizza1</span>
                        <span class="product-subhead">Subhead</span>
                    </div>
                    <div class="product-image-placeholder">
                        <!-- Placeholder for image -->
                        <span>🍕</span>
                    </div>
                    <button class="add-to-cart">Ajouter Au Panier</button>
                </div>
                <div class="product-card">
                    <div class="product-header">
                        <span class="product-title">Pizza1</span>
                        <span class="product-subhead">Subhead</span>
                    </div>
                    <div class="product-image-placeholder">
                        <!-- Placeholder for image -->
                        <span>🍕</span>
                    </div>
                    <button class="add-to-cart">Ajouter Au Panier</button>
                </div>

   

               
            </div>
        </div>
        <div class="cart-panel open" style="width: 50vw;height: 50vh; bottom: 0;right: 0; position: absolute;background-color: antiquewhite;z-index: 0;">
            <button class="cart-btn"><span class="cart-icon">🛒</span>
                <div class="cart-item">
                    <span class="item-count">3</span>
                    <span class="item-name">Pizza1</span>
                    <span class="item-price">12.99€</span>
                </div>
                <div class="cart-item">
                    <span class="item-count">3</span>
                    <span class="item-name">Pizza1</span>
                    <span class="item-price">12.99€</span>
                </div>
                <div class="cart-item">
                    <span class="item-count">3</span>
                    <span class="item-name">Pizza1</span>
                    <span class="item-price">12.99€</span>
                </div>
                <button class="checkout-btn">Passer à la caisse</button>
            </button>
                
        </div>

        <!-- Small floating toggle to open/close the cart -->
        <button class="cart-toggle" aria-label="Basculer le panier">🛒</button>

   </div>
    <!-- Scripts -->
    <script src="scripts/test.js"></script>
</body>
</html>