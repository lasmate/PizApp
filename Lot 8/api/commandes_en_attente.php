<?php
// requis pour api rest
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../ConnexionBDD.php';

// Vérifier si la méthode de requête est correcte
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    // Récupérer les commandes avec le statut 'en attente' (idetat = 4)
    $query = "SELECT c.idcommande, c.date_heure_commande, c.montant_ttc, u.login_utilisateur 
              FROM commande c
              JOIN utilisateur u ON c.iduser = u.iduser
              WHERE c.idetat = 4
              ORDER BY c.date_heure_commande ASC";
              
    $result = mysqli_query($conn, $query);
    
    $orders = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $order_id = $row['idcommande'];
            
            // Récupérer les détails pour chaque commande
            $details_query = "SELECT p.nomproduit, l.quantite, l.total_ht 
                              FROM ligne_de_commande l
                              JOIN produit p ON l.idproduit = p.idproduit
                              WHERE l.idcommande = $order_id";
            
            $details_result = mysqli_query($conn, $details_query);
            $lines = array();
            
            if ($details_result) {
                while ($detail = mysqli_fetch_assoc($details_result)) {
                    $lines[] = $detail;
                }
            }

            $row['lignes'] = $lines;
            $orders[] = $row;
        }
        
        http_response_code(200);
        echo json_encode($orders);
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Erreur de base de données : " . mysqli_error($conn)));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
