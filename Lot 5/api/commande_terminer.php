<?php
// requis pour api rest
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../ConnexionBDD.php';

// Vérifier si la méthode de requête est correcte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Récupérer les données brutes postées
    $data = json_decode(file_get_contents("php://input"));

    // Valider l'entrée
    if (!empty($data->id)) {
        
        $id = mysqli_real_escape_string($conn, $data->id);

        // Mettre à jour le statut à 'prête' (idetat = 7)
        $query = "UPDATE commande SET idetat = 7 WHERE idcommande = '$id'";

        if(mysqli_query($conn, $query)) {
            http_response_code(200);
            echo json_encode(array("message" => "Commande terminée."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Impossible de mettre à jour la commande."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
