<?php
/**
 * CreateAccount.php
 * Page d'inscription et traitement de la création de compte
 */
    include 'ConnexionBDD.php';
    $message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = mysqli_real_escape_string($conn, $_POST['nom']);
        $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password !== $password2) {
            $message = "<span style='color:red'>Les mots de passe ne correspondent pas.</span>";
        } else {
            // Vérifier si l'email existe déjà
            $check = mysqli_query($conn, "SELECT * FROM utilisateur WHERE email_utilisateur='$email'");
            if (mysqli_num_rows($check) > 0) {
                $message = "<span style='color:red'>Cet email est déjà utilisé.</span>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $login = $nom . '_' . $prenom;
                $sql = "INSERT INTO utilisateur (login_utilisateur, email_utilisateur, mot_de_passe_utilisateur) VALUES ('$login', '$email', '$hashed_password')";
                if (mysqli_query($conn, $sql)) {
                    $message = "<span style='color:green'>Compte créé avec succès !</span>";
                } else {
                    $message = "<span style='color:red'>Erreur lors de la création du compte.</span>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - AppResto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="slide-in-blurred-top">
    <?php
    include_once 'navbar.php';
    renderNavbar('inscription');
    ?>

    <div class="HeroCard">
        <div class="LandingText">
            <h1>AppResto</h1>
            <h3>Votre Premier stop pour un creux</h3>
        </div>
        <?php if (!empty($message)) echo '<div style="margin-bottom:1rem">'.$message.'</div>'; ?>
        <form class="connect-form" action="#" method="post">
            <fieldset style="border:0;padding:0;display:flex;flex-direction:column;align-items:center;gap:1rem">
                <div style="display:flex;gap:1rem">
                    <div class="input-box">
                        <input type="text" name="nom" placeholder="Nom" required>
                        <span class="input-icon">✕</span>
                    </div>
                    <div class="input-box">
                        <input type="text" name="prenom" placeholder="Prenom" required>
                        <span class="input-icon">✕</span>
                    </div>
                </div>

                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <span class="input-icon">✕</span>
                </div>

                <div style="display:flex;gap:1rem">
                    <div class="input-box">
                        <input type="password" name="password" placeholder="Mot de passe" required>
                        <span class="input-icon">✕</span>
                    </div>
                    <div class="input-box">
                        <input type="password" name="password2" placeholder="Verifiez votre mot de passe" required>
                        <span class="input-icon">✕</span>
                    </div>
                </div>

                <div style="display:flex;gap:1rem;justify-content:center">
                    <button type="submit" class="primary-btn"><span class="btn-icon">★</span>Créer un compte</button>
                    <button type="button" class="primary-btn" onclick="location.href='connexion.php'"><span class="btn-icon">★</span>Déjà inscrit ? Se connecter</button>
                </div>
            </fieldset>
        </form>

    </div>
    <script src="scripts/main.js"></script>
    <script src="scripts/form-helpers.js"></script>
</body>
</html>
