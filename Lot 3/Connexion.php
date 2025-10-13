<?php
include 'ConnexionBDD.php';
session_start();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $sql = "SELECT * FROM utilisateur WHERE email_utilisateur='$email'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['mot_de_passe_utilisateur'])) {
            $_SESSION['user_id'] = $row['iduser'];
            $_SESSION['user_login'] = $row['login_utilisateur'];
            header('Location: productlist.php');
            exit();
        } else {
            $message = "<span style='color:red'>Mot de passe incorrect.</span>";
        }
    } else {
        $message = "<span style='color:red'>Aucun compte trouvé avec cet email.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AppResto</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<?php
include_once 'navbar.php';
renderNavbar('connexion');
?>
<body class="slide-in-blurred-top">
    <div class="HeroCard">
        <div class="LandingText">
        <h1>AppResto</h1>
        <h3>Votre premier stop pour un creux</h3>
        </div>
        <div>
            <?php if (!empty($message)) echo '<div style="margin-bottom:1rem">'.$message.'</div>'; ?>
            <form class="connect-form" action="" method="post">
                <fieldset style="border:0;padding:0;display:flex;flex-direction:column;align-items:center;gap:1rem">
                    <div class="input-box">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <span class="input-icon">✕</span>
                    </div>
                    <div class="input-box">
                        <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                        <span class="input-icon">✕</span>
                    </div>
                    <button type="submit" class="primary-btn"><span class="btn-icon">●</span>Connexion</button>
                </fieldset>
            </form>
        </div>
    </div>
    <script src="scripts/main.js"></script>
    <script src="scripts/form-helpers.js"></script>
</body>
</html>