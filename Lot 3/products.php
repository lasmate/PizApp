<?php
/**
 * products.php
 * Central place to fetch products from the database.
 */

/**
 * Get products from the database
 * Returns an array shaped for renderProductCard consumption
 *
 * Each product: [
 *   'id' => int,
 *   'type' => string,
 *   'title' => string,
 *   'subhead' => string,
 *   'price' => float|null,
 *   'image' => string|null (URL or image path) 
 * ]
 */
function getSampleProducts() {
    // Use existing DB connection settings
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

        // Only use DB image if it looks like an actual image path or URL.
        // Otherwise, pass null to let renderProductCard choose a fitting emoji.
        $useImage = null;
        if ($rawImage !== '' && $rawImage !== 'img/') {
            $isUrl = filter_var($rawImage, FILTER_VALIDATE_URL) !== false;
            $looksLikeImagePath = preg_match('/\.(png|jpe?g|gif|svg|webp)$/i', $rawImage) === 1;
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
