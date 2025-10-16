<?php
/**
 * Render Cart Modal
 * The cart items will be populated dynamically from cookies via JavaScript
 */
function renderCart() {
    return '
        <div class="cart-backdrop" aria-hidden="true"></div>
        <div class="cart-modal" role="dialog" aria-labelledby="cart-title" aria-modal="true">
            <div class="cart-modal-header">
                <h3 id="cart-title">Votre panier</h3>
                <button class="cart-close" aria-label="Fermer">✕</button>
            </div>
            <div class="cart-modal-content" id="cart-items-container">
                <!-- Cart items will be dynamically inserted here from cookie -->
                <p class="empty-cart-message">Votre panier est vide.</p>
            </div>
            <div class="cart-modal-footer">
                <div class="cart-total">
                    <strong>Total: <span id="cart-total-price">0.00€</span></strong>
                </div>
                <button class="checkout-btn">Passer à la caisse</button>
            </div>
        </div>';
}
?>