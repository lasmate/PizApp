<?php
function renderProductCard($title, $subhead, $image) {
    return '
    <div class="product-card">
        <div class="product-header">
            <span class="product-title">' . htmlspecialchars($title) . '</span>
            <span class="product-subhead">' . htmlspecialchars($subhead) . '</span>
        </div>
        <div class="product-image-placeholder">
            <img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($title) . '">
        </div>
        <button class="add-to-cart">Ajouter Au Panier</button>
    </div>';
}
?>