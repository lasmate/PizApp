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
 * Get sample products data
 * In a real application, this would come from a database
 */
function getSampleProducts() {
    return [
        [
            'id' => 1,
            'title' => 'Pizza Margherita',
            'subhead' => 'Tomates, mozzarella, basilic',
            'price' => 12.50,
            'image' => '🍕'
        ],
        [
            'id' => 2,
            'title' => 'Pizza Pepperoni',
            'subhead' => 'Pepperoni, mozzarella, sauce tomate',
            'price' => 14.90,
            'image' => '🍕'
        ],
        [
            'id' => 3,
            'title' => 'Pizza 4 Fromages',
            'subhead' => 'Mozzarella, gorgonzola, parmesan, chèvre',
            'price' => 16.50,
            'image' => '🍕'
        ],
        [
            'id' => 4,
            'title' => 'Coca Cola',
            'subhead' => 'Boisson gazeuse 33cl',
            'price' => 2.50,
            'image' => '🥤'
        ],
        [
            'id' => 5,
            'title' => 'Tiramisu',
            'subhead' => 'Dessert italien traditionnel',
            'price' => 6.90,
            'image' => '🍰'
        ],
        [
            'id' => 6,
            'title' => 'Salade César',
            'subhead' => 'Salade, poulet, croûtons, parmesan',
            'price' => 11.50,
            'image' => '🥗'
        ],
        [
            'id' => 7,
            'title' => 'Eau Minérale',
            'subhead' => 'Eau plate 50cl',
            'price' => 1.80,
            'image' => '💧'
        ],
        [
            'id' => 8,
            'title' => 'Panna Cotta',
            'subhead' => 'Dessert italien aux fruits rouges',
            'price' => 5.90,
            'image' => '🍮'
        ]
    ];
}
?>