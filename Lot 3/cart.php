<?php
/**
 * Sliding Cart Panel Component
 * - Toggle handle: small semicircle on the right side of the screen
 * - Panel slides in from right and lists cart items
 * - Each item: title, unit price, quantity controls (+/-), subtotal, remove button
 */

function renderCartPanel() {
    echo ' 
    <div class="cart-toggle" aria-label="Ouvrir le panier" title="Panier">
        <div class="cart-toggle-badge" id="cart-count-badge">0</div>
        <div class="cart-toggle-icon">🧺</div>
    </div>

    <aside class="cart-panel" aria-hidden="true" aria-label="Panneau Panier">
        <header class="cart-panel-header">
            <h3>Votre panier</h3>
            <button class="cart-close" aria-label="Fermer">✕</button>
        </header>
        <div class="cart-items" id="cart-items"></div>
        <footer class="cart-panel-footer">
            <div class="cart-total-row">
                <span>Total</span>
                <strong id="cart-total">0.00€</strong>
            </div>
            <div class="cart-actions">
                <button class="cart-clear" id="cart-clear">Vider</button>
                <a class="checkout-btn" id="cart-checkout" href="#">Passer à la caisse</a>
            </div>
        </footer>
    </aside>';
}
?>
