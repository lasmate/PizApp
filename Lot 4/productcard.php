<?php
/**
 * Composant Carte Produit
 * Génère une carte produit avec titre, sous-titre, image/emoji et bouton d'ajout au panier
 */

function renderProductCard($title, $subhead, $image = null, $price = null, $type = null, $productId = null) {
    $productId = $productId ?? uniqid(); // Génère un ID unique si non fourni
        // Conserve le prix numérique pour les attributs data et une chaîne formatée pour l'affichage
        $priceNumeric = isset($price) && $price !== '' ? (float)$price : null;
        $priceDisplay = $priceNumeric !== null ? number_format($priceNumeric, 2) . '€' : '';
    
    // valeur de secours (emoji) si aucune image fournie
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
    
    $subheadHtml = '';
    $normalizedSubhead = str_replace('_', ' ', (string) $subhead);
    $subheadParts = array_filter(array_map('trim', preg_split('/[,;\n]+/', $normalizedSubhead))); // Sépare par virgules, points-virgules ou sauts de ligne

    if (!empty($subheadParts)) {
        $subheadHtml .= '<ul class="product-subhead-list">';
        foreach ($subheadParts as $part) {
            $subheadHtml .= '<li>' . htmlspecialchars($part) . '</li>';
        }
        $subheadHtml .= '</ul>';
    }

    return '
    <div class="product-card" data-product-id="' . htmlspecialchars($productId) . '" data-product-type="' . htmlspecialchars($type ?? '') . '">
        <div class="product-header">
            <span class="product-title">' . htmlspecialchars($title) . '</span>
            ' . $subheadHtml . '
        </div>
        <div class="product-image">
            <img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($title) . '">

        </div>

            ' . ($priceDisplay ? '<div class="product-price">' . $priceDisplay . '</div>' : '') . '
        <button class="add-to-cart" 
                data-product-id="' . htmlspecialchars($productId) . '"
                data-product-title="' . htmlspecialchars($title) . '"
                    data-product-price="' . htmlspecialchars($priceNumeric !== null ? number_format($priceNumeric, 2, '.', '') : '0.00') . '">
            Ajouter Au Panier
        </button>
    </div>';
}

?>