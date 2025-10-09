<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des produits - AppResto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="slide-in-blurred-top">
    <nav>
        <div class="nav-item active" onclick="location.href='connexion.php'"><span class="icon-circle"><img src="img/input_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Connexion</div>
        <div class="nav-item" onclick="location.href='index.php'"><span class="icon-circle"><img src="img/cottage_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Acceuil</div>
        <div class="nav-item" onclick="location.href='inscription.php'"><span class="icon-circle"><img src="img/group_add_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Inscription</div>
    </nav>
    <div class="HeroCard">
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
                        <span>🍕</span>
                    </div>
                    <button class="add-to-cart">Ajouter Au Panier</button>
                </div>

               
            </div>
        </div>
        <div class="cart-slideout" id="cartSlideout">
            <div class="cart-header">
                <h2>Mon Panier</h2>
                <button class="close-cart" onclick="toggleCart()">&times;</button>
            </div>
            <div class="cart-content">
                <p>Votre panier est vide.</p>
                <!-- Ici, les éléments du panier seront ajoutés dynamiquement -->
            </div>
        </div>
        <button class="cart-fab" onclick="toggleCart()" id="cartFab">
            <img src="img/for_you_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg" alt="Panier" />
        </button>
        <script>
        function toggleCart() {
            const cart = document.getElementById('cartSlideout');
            cart.classList.toggle('open');
        }
        </script>
    </div>
</body>
</html>