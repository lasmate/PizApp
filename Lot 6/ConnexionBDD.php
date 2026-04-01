<?php
/**
 * ConnexionBDD.php
 * Configuration et initialisation de la connexion MySQL (mysqli)
 */
  $server = "localhost";
  $username = "root";
  $password = ""; 
  $db = "db_pizapp";
  $conn = mysqli_connect($server, $username, $password, $db);
  if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error() . "<br>");
}
?>