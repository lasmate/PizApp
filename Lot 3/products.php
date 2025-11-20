<?php
/**
 * Récupère les produits depuis la base de données
 * Renvoie un tableau formaté pour être utilisé par renderProductCard
 *
 * Chaque produit : [
 *   'id' => int,
 *   'type' => string,
 *   'title' => string,
 *   'subhead' => string,
 *   'price' => float|null,
 *   'image' => string|null (URL ou chemin d'image)
 * ]
 */
function getSampleProducts() {
    // Utilise la configuration de connexion DB existante
    require_once __DIR__ . '/ConnexionBDD.php';

    if (!isset($conn) || !$conn) {
        error_log('Connexion DB non disponible.');
        return [];
    }

    $sql = 'SELECT idproduit, typeproduit, nomproduit, libproduit, prixproduit, imgproduit FROM produit ORDER BY idproduit ASC';
    $res = mysqli_query($conn, $sql);
    if ($res === false) {
        error_log('Erreur SQL (produit): ' . mysqli_error($conn));
        return [];
    }

    $products = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $rawImage = isset($row['imgproduit']) ? trim((string)$row['imgproduit']) : '';

        // N'utilise l'image de la BDD que si elle ressemble à un chemin ou une URL d'image.
        // Sinon, passe null pour laisser renderProductCard choisir un emoji approprié.
        $useImage = null;
        if ($rawImage !== '' && $rawImage !== 'img/') {
            $isUrl = filter_var($rawImage, FILTER_VALIDATE_URL) !== false;
            $looksLikeImagePath = preg_match('/\.(png|jpe?g|jpg|gif|svg|webp)$/i', $rawImage) === 1;
            if ($isUrl || $looksLikeImagePath) {
                $useImage = $rawImage;
            }
        }

        $products[] = [
            'id' => (int) $row['idproduit'],
            'type' => (string) $row['typeproduit'],
            'title' => (string) $row['nomproduit'],
            'subhead' => (string) ($row['libproduit'] ?? ''),
            'price' => isset($row['prixproduit']) ? (float) $row['prixproduit'] : null,
            'image' => $useImage,
        ];
    }

    mysqli_free_result($res);
    return $products;
}
?>
