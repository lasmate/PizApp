<?php
/**
 * Product Card Component
 * Renders a product card with title, subtitle, image/icon and add to cart button
 */

function renderProductCard($title, $subhead, $image = null, $price = null, $productId = null) {
    $productId = $productId ?? uniqid(); // Generate unique ID if not provided
    $price = $price ? number_format($price, 2) . '€' : '';
    
    // If no image provided, use emoji placeholder based on product type
    if (!$image) {
        $lowerTitle = strtolower($title);
        if (strpos($lowerTitle, 'pizza') !== false) {
            $image = '🍕';
        } elseif (strpos($lowerTitle, 'boisson') !== false || strpos($lowerTitle, 'drink') !== false) {
            $image = '🥤';
        } elseif (strpos($lowerTitle, 'dessert') !== false) {
            $image = '🍰';
        } else {
            $image = '🍽️';
        }
    }
    
    return '
    <div class="product-card" data-product-id="' . htmlspecialchars($productId) . '">
        <div class="product-header">
            <span class="product-title">' . htmlspecialchars($title) . '</span>
            <span class="product-subhead">' . htmlspecialchars($subhead) . '</span>
        </div>
        <div class="product-image-placeholder">
            ' . (filter_var($image, FILTER_VALIDATE_URL) ? 
                '<img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($title) . '">' : 
                '<span>' . $image . '</span>') . '
        </div>
        ' . ($price ? '<div class="product-price">' . $price . '</div>' : '') . '
        <button class="add-to-cart" data-product-id="' . htmlspecialchars($productId) . '">Ajouter Au Panier</button>
    </div>';
}

/**
 * Get products from the database
 * Returns an array shaped for renderProductCard consumption
 */
function getSampleProducts() {
    // Use existing DB connection settings
    require_once __DIR__ . '/ConnexionBDD.php';

    if (!isset($conn) || !$conn) {
        error_log('Connexion DB non disponible.');
        return [];
    }

    // Ensure UTF-8 for proper accents handling
    if (!@mysqli_set_charset($conn, 'utf8mb4')) {
        // Not fatal, but log it
        error_log('Impossible de définir le charset MySQL en utf8mb4: ' . mysqli_error($conn));
    }

    $sql = 'SELECT idproduit, nomproduit, libproduit, prixproduit, imgproduit FROM produit ORDER BY idproduit ASC';
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