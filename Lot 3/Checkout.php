<?php
/**
 * Render Checkout Modal
 * populated from cookies via JavaScript
 */
function renderCheckout() {
    return '
        <div class="checkout-backdrop" aria-hidden="true"></div>
        <div class="checkout-modal" role="dialog" aria-labelledby="checkout-title" aria-modal="true">
            <div class="checkout-modal-header">
                <h3 id="checkout-title">Passer à la caisse</h3>
                <button class="checkout-close" aria-label="Fermer">✕</button>
            </div>
            <div class="checkout-modal-content" id="checkout-items-container">
                <!-- Checkout items will be dynamically inserted here from cookie -->
                <p class="empty-checkout-message">Votre panier est vide.</p>
            </div>
            <div class="checkout-modal-footer">
                <div class="checkout-total">
                    <strong>Total à payer: <span id="checkout-total-price">0.00€</span></strong>
                </div>
                <button class="confirm-checkout-btn">Confirmer le paiement</button>
            </div>
        </div>';
}
?>
