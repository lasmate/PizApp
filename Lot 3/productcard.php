<?php
/**
 * Product Card Component
 * Renders a product card with title, subtitle, image/icon and add to cart button
 */

function renderProductCard($title, $subhead, $image = null, $price = null, $type = null, $productId = null) {
    $productId = $productId ?? uniqid(); // Generate unique ID if not provided
        // Keep numeric price for data attributes and a separate formatted display string
        $priceNumeric = isset($price) && $price !== '' ? (float)$price : null;
        $priceDisplay = $priceNumeric !== null ? number_format($priceNumeric, 2) . '€' : '';
    
    // fallback to emoji if no images provided
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
    $subheadParts = array_filter(array_map('trim', preg_split('/[,;\n]+/', $normalizedSubhead))); # Split by commas, semicolons, or new lines

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
        <div class="product-image-placeholder">
            ' . ((filter_var($image, FILTER_VALIDATE_URL)
            || preg_match('/^(\/|\.\/|\.\.\/).+\.(jpe?g|png|gif|webp|svg)(\?.*)?$/i', $image)// 
            || preg_match('/.+\.(jpe?g|png|gif|webp|svg)$/i', $image))
            ? '<img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($title) . '">'
            : '<span>' . htmlspecialchars($image) . '</span>') . '
        </div>
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

// Note: getSampleProducts() has been moved to products.php
?>