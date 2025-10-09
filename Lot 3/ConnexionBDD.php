<?php
  $server = "localhost";
  $username = "root";
  $password = ""; // Mets ici ton mot de passe si besoin
  $db = "db_pizapp";
  $conn = mysqli_connect($server, $username, $password, $db);
  if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error() . "<br>");
}
?>