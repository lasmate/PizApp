<?php
/**
 * Panneau panier coulissant
 * - Poignée de bascule : petit demi-cercle sur le côté droit de l'écran
 * - Le panneau glisse depuis la droite et liste les éléments du panier
 * - Chaque ligne : titre, prix unitaire, contrôles de quantité (+/-), sous-total, bouton supprimer
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
    </aside>

    <!-- Secondary checkout panel overlaying the cart panel -->
    <aside class="checkout-panel" aria-hidden="true" aria-label="Panneau Paiement">
        <header class="cart-panel-header">
            <h3>Paiement</h3>
            <button class="checkout-close" aria-label="Fermer">✕</button>
        </header>
        <div class="checkout-content">
            <div class="service-type">
                <label class="service-chip active" data-type="1">A emporter</label>
                <label class="service-chip" data-type="0">Sur place</label>
            </div>
            <form class="payment-form" id="payment-form" autocomplete="off">
                <div class="form-row">
                    <label for="card-name">Nom sur la carte</label>
                    <input type="text" id="card-name" name="cardName" placeholder="Jean Dupont" required>
                </div>
                <div class="form-row">
                    <label for="card-number">Numéro de carte</label>
                    <input type="text" id="card-number" name="cardNumber" inputmode="numeric" pattern="[0-9\s]{12,19}" placeholder="1234 5678 9012 3456" required>
                </div>
                <div class="form-row two-cols">
                    <div>
                        <label for="card-exp">Expiration</label>
                        <input type="text" id="card-exp" name="cardExp" inputmode="numeric" placeholder="MM/AA" required>
                    </div>
                    <div>
                        <label for="card-cvv">CVV</label>
                        <input type="password" id="card-cvv" name="cardCvv" inputmode="numeric" maxlength="4" placeholder="***" required>
                    </div>
                </div>
            </form>
        </div>
        <footer class="cart-panel-footer">
            <div class="cart-actions">
                <button class="cart-clear" id="checkout-back">Retour</button>
                <button class="checkout-btn" id="confirm-payment">Confirmer le paiement</button>
            </div>
        </footer>
    </aside>';
}
?>
