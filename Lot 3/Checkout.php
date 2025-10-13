<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation - AppResto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="slide-in-blurred-top">
    <nav>
        <div class="nav-item active" onclick="location.href='connexion.php'"><span class="icon-circle"><img src="img/input_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Connexion</div>
        <div class="nav-item" onclick="location.href='index.php'"><span class="icon-circle"><img src="img/cottage_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Acceuil</div>
        <div class="nav-item" onclick="location.href='CreateAccount.php'"><span class="icon-circle"><img src="img/group_add_24dp_000000_FILL0_wght400_GRAD0_opsz24.svg"></span>Inscription</div>
    </nav>
    <div class="HeroCard">
        <div class="LandingText">
        <h1>AppResto</h1>
        <h3>Votre premier stop pour un creux</h3>
        </div>
        <div>
            <form class="connect-form" action="Send">
                <fieldset style="border:0;padding:0;display:flex;flex-direction:column;align-items:center;gap:1rem">
                    <div class="input-box">
                        <input type="numero-carte" id="numero-carte" name="numero-carte" placeholder="Numero de carte" required>
                        <span class="input-icon">✕</span>
                    </div>
                    <div class="input-box">
                        <input type="cvc" id="cvc" name="cvc" placeholder="CVC" required>
                        <span class="input-icon">✕</span>
                    </div>
                    <div class="input-box">
                        <input type="date-expiration" id="date-expiration" name="date-expiration" placeholder="Date d'expiration" required>
                        <span class="input-icon">✕</span>
                    </div>
                    <button type="submit" class="primary-btn"><span class="btn-icon">●</span>Validé</button>
                </fieldset>
            </form>
        </div>
    </div>
    <script src="scripts/main.js"></script>
    <script src="scripts/form-helpers.js"></script>
</body>
</html>