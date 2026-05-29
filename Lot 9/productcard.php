<?php
/**
 * Composant Carte Produit
 * Génère une carte produit avec titre, sous-titre, image/emoji et bouton d'ajout au panier
 */

require_once __DIR__ . '/products.php';

class ProductCardRenderer {
    public function renderProduct(Product $product) {
        $productId = $product->getId() ?: uniqid();
        $title = $product->getTitle();
        $subhead = $product->getSubhead();
        $type = $product->getType();
        $image = $product->getImage();
        $priceNumeric = $product->getPrice();
        $priceDisplay = $priceNumeric !== null ? number_format((float) $priceNumeric, 2) . '€' : '';

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
        $subheadParts = array_filter(array_map('trim', preg_split('/[,;\n]+/', $normalizedSubhead)));

        if (!empty($subheadParts)) {
            $subheadHtml .= '<ul class="product-subhead-list">';
            foreach ($subheadParts as $part) {
                $subheadHtml .= '<li>' . htmlspecialchars($part) . '</li>';
            }
            $subheadHtml .= '</ul>';
        }

        $imageHtml = '';
        if (preg_match('/\.(png|jpe?g|jpg|gif|svg|webp)$/i', (string) $image) === 1 || filter_var((string) $image, FILTER_VALIDATE_URL) !== false) {
            $imageHtml = '<img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($title) . '">';
        } else {
            $imageHtml = '<span class="product-emoji" aria-hidden="true">' . htmlspecialchars((string) $image) . '</span>';
        }

        return '
    <div class="product-card" data-product-id="' . htmlspecialchars((string) $productId) . '" data-product-type="' . htmlspecialchars($type ?? '') . '">
        <div class="product-header">
            <span class="product-title">' . htmlspecialchars($title) . '</span>
            ' . $subheadHtml . '
        </div>
        <div class="product-image">
            ' . $imageHtml . '
        </div>

            ' . ($priceDisplay ? '<div class="product-price">' . $priceDisplay . '</div>' : '') . '
        <button class="add-to-cart" 
                data-product-id="' . htmlspecialchars((string) $productId) . '"
                data-product-title="' . htmlspecialchars($title) . '"
                    data-product-price="' . htmlspecialchars($priceNumeric !== null ? number_format((float) $priceNumeric, 2, '.', '') : '0.00') . '">
            Ajouter Au Panier
        </button>
    </div>';
    }
}

function renderProductCard($title, $subhead, $image = null, $price = null, $type = null, $productId = null) {
    $renderer = new ProductCardRenderer();
    $product = new Product($productId ?? 0, $type ?? '', $title, $subhead, $price, $image);
    return $renderer->renderProduct($product);
}

?>