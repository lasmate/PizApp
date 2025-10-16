/**
 * Cart Modal Management & Cookie Storage
 * - Hidden on load
 * - Small toggle button inside HeroCard bottom-right
 * - Stores cart items in cookies and dynamically renders them
 */

(function () {
	const hero = document.querySelector('.HeroCard');
	const modal = document.querySelector('.cart-modal');
	const backdrop = document.querySelector('.cart-backdrop');
	const toggleBtn = document.querySelector('.cart-toggle');
	const closeBtn = document.querySelector('.cart-close');
	const cartItemsContainer = document.getElementById('cart-items-container');
	const cartTotalPrice = document.getElementById('cart-total-price');

	// New selectors for redesigned toggles and modals
	const cartToggleSemi = document.querySelector('.cart-toggle-semicircle');
	const checkoutToggleSemi = document.querySelector('.checkout-toggle-semicircle');
	let checkoutModal = document.querySelector('.checkout-modal');

	if (!hero || !modal || !backdrop || !toggleBtn) return;

	// Ensure closed on load
	modal.classList.remove('open');
	backdrop.classList.remove('open');

	// Create checkout modal if not present - full structure (header, content, footer)
	if (!checkoutModal) {
		checkoutModal = document.createElement('div');
		checkoutModal.className = 'checkout-modal';
		checkoutModal.innerHTML = `
			<div class="checkout-modal-header">
				<h3 id="checkout-title">Paiement</h3>
				<button class="checkout-close" aria-label="Fermer">✕</button>
			</div>
			<div class="checkout-modal-content">
				<form id="checkout-form" class="checkout-form" novalidate>
					<div class="input-box">
						<input id="cardholder" name="cardholder" type="text" placeholder="Titulaire de la carte" required>
						<span class="input-icon">✕</span>
					</div>
					<div class="input-box">
						<input id="cardnumber" name="cardnumber" type="text" inputmode="numeric" placeholder="1111 2222 3333 4444" maxlength="19" required>
						<span class="input-icon">✕</span>
					</div>
					<div style="display:flex;gap:0.5rem;">
						<div class="input-box" style="flex:1">
							<input id="expiry" name="expiry" type="text" placeholder="MM/AA" maxlength="5" required>
							<span class="input-icon">✕</span>
						</div>
						<div class="input-box" style="width:100px;">
							<input id="cvv" name="cvv" type="password" inputmode="numeric" placeholder="CVV" maxlength="4" required>
							<span class="input-icon">✕</span>
						</div>
					</div>
					<div id="checkout-summary" style="margin-top:1rem"></div>
					<div style="display:flex;gap:0.5rem;margin-top:1rem;">
						<button id="pay-btn" class="primary-btn" type="submit"><span class="btn-icon">●</span>Payer</button>
						<button id="cancel-btn" class="secondary-btn" type="button">Annuler</button>
						<div id="checkout-error" style="color:#c00;margin-left:1rem;display:none"></div>
					</div>
				</form>
			</div>
			<div class="checkout-modal-footer">
				<div style="font-size:0.85rem;color:var(--bg-purple-lighter)">Paiement sécurisé (simulé)</div>
			</div>
		`;
		document.body.appendChild(checkoutModal);
	}

	// ========== COOKIE MANAGEMENT ==========
	
	/**
	 * Set a cookie
	 */
	function setCookie(name, value, days = 7) {
		const expires = new Date();
		expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
		document.cookie = `${name}=${encodeURIComponent(value)};expires=${expires.toUTCString()};path=/`;
	}

	/**
	 * Get a cookie value
	 */
	function getCookie(name) {
		const nameEQ = name + '=';
		const cookies = document.cookie.split(';');
		for (let i = 0; i < cookies.length; i++) {
			let cookie = cookies[i].trim();
			if (cookie.indexOf(nameEQ) === 0) {
				return decodeURIComponent(cookie.substring(nameEQ.length));
			}
		}
		return null;
	}

	// ========== CART OPERATIONS ==========

	/**
	 * Get cart from cookie
	 */
	function getCart() {
		const cartData = getCookie('shopping_cart');
		if (!cartData) return [];
		try {
			return JSON.parse(cartData);
		} catch (e) {
			console.error('Error parsing cart cookie:', e);
			return [];
		}
	}

	/**
	 * Save cart to cookie
	 */
	function saveCart(cart) {
		setCookie('shopping_cart', JSON.stringify(cart), 7);
	}

	/**
	 * Add item to cart
	 */
	function addToCart(productId, title, price) {
		const cart = getCart();
		
		// Check if item already exists
		const existingItem = cart.find(item => item.id === productId);
		
		if (existingItem) {
			existingItem.quantity += 1;
		} else {
			cart.push({
				id: productId,
				title: title,
				price: parseFloat(price),
				quantity: 1
			});
		}
		
		saveCart(cart);
		updateCartDisplay();
	}

	/**
	 * Update item quantity
	 */
	function updateQuantity(productId, newQuantity) {
		let cart = getCart();
		
		if (newQuantity <= 0) {
			// Remove item
			cart = cart.filter(item => item.id !== productId);
		} else {
			const item = cart.find(item => item.id === productId);
			if (item) {
				item.quantity = newQuantity;
			}
		}
		
		saveCart(cart);
		updateCartDisplay();
	}

	/**
	 * Calculate cart total
	 */
	function calculateTotal(cart) {
		return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
	}

	/**
	 * Update cart display in modal using the specified structure
	 */
	function updateCartDisplay() {
		const cart = getCart();
		
		if (!cartItemsContainer) return;
		
		if (cart.length === 0) {
			cartItemsContainer.innerHTML = '<p class="empty-cart-message">Votre panier est vide.</p>';
			if (cartTotalPrice) cartTotalPrice.textContent = '0.00€';
			updateCartBadge(0);
			return;
		}
		
		// Build cart items HTML using the specified structure
		let html = '';
		cart.forEach(item => {
			const itemTotal = (item.price * item.quantity).toFixed(2);
			html += `
				<div class="cart-item" data-product-id="${item.id}">
					<span class="item-count">${item.quantity}</span>
					<span class="item-name">${item.title}</span>
					<span class="item-price">${itemTotal}€</span>
					<button class="item-remove" data-product-id="${item.id}" aria-label="Retirer">×</button>
				</div>
			`;
		});
		
		cartItemsContainer.innerHTML = html;
		
		// Update total price
		const total = calculateTotal(cart);
		if (cartTotalPrice) cartTotalPrice.textContent = total.toFixed(2) + '€';
		
		// Update cart badge
		const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
		updateCartBadge(totalItems);
		
		// Attach event listeners to remove buttons
		attachRemoveListeners();
	}

	/**
	 * Attach event listeners to remove buttons
	 */
	function attachRemoveListeners() {
		document.querySelectorAll('.item-remove').forEach(btn => {
			btn.addEventListener('click', (e) => {
				const productId = e.currentTarget.getAttribute('data-product-id');
				updateQuantity(productId, 0);
			});
		});
	}

	/**
	 * Update cart badge count
	 */
	function updateCartBadge(count) {
		let badge = toggleBtn.querySelector('.cart-badge');
		if (count > 0) {
			if (!badge) {
				badge = document.createElement('span');
				badge.className = 'cart-badge';
				toggleBtn.appendChild(badge);
			}
			badge.textContent = count;
		} else {
			if (badge) badge.remove();
		}
	}

	/**
	 * Show notification
	 */
	function showNotification(message) {
		let notif = document.querySelector('.cart-notification');
		if (!notif) {
			notif = document.createElement('div');
			notif.className = 'cart-notification';
			document.body.appendChild(notif);
		}
		
		notif.textContent = message;
		notif.classList.add('show');
		
		setTimeout(() => {
			notif.classList.remove('show');
		}, 2000);
	}

	// ========== MODAL ANIMATIONS ==========

	function openCart() {
		// Trigger roll animation on the button
		toggleBtn.classList.add('roll');
		// Start states are already defined in CSS; just flip to open state
		backdrop.classList.add('open');
		modal.classList.add('open');
		// Remove the roll class after animation ends
		setTimeout(() => toggleBtn.classList.remove('roll'), 450);
		
		// Update cart display when opening
		updateCartDisplay();
	}

	function closeCart() {
		backdrop.classList.remove('open');
		modal.classList.remove('open');
	}

	// ========== EVENT LISTENERS ==========

	toggleBtn.addEventListener('click', () => {
		const isOpen = modal.classList.contains('open');
		if (isOpen) closeCart(); else openCart();
	});

	backdrop.addEventListener('click', closeCart);
	if (closeBtn) closeBtn.addEventListener('click', closeCart);

	// Add to cart buttons
	document.querySelectorAll('.add-to-cart').forEach(btn => {
		btn.addEventListener('click', (e) => {
			const button = e.currentTarget;
			const productId = button.getAttribute('data-product-id');
			const productTitle = button.getAttribute('data-product-title');
			const productPrice = button.getAttribute('data-product-price');
			
			addToCart(productId, productTitle, productPrice);
			
			// Add a little animation to the button
			button.style.transform = 'scale(0.95)';
			setTimeout(() => {
				button.style.transform = 'scale(1)';
			}, 150);
		});
	});

	// Cart toggle event
	if (cartToggleSemi && modal) {
		cartToggleSemi.addEventListener('click', () => {
			modal.classList.toggle('open');
			// Close checkout if open
			if (checkoutModal) checkoutModal.classList.remove('open');
		});
	}

	// Checkout toggle event
	if (checkoutToggleSemi && checkoutModal) {
		checkoutToggleSemi.addEventListener('click', () => {
			checkoutModal.classList.toggle('open');
			// Close cart if open
			modal.classList.remove('open');
		});
	}

	// Checkout close button
	checkoutModal.addEventListener('click', function(e) {
		if (e.target.classList.contains('checkout-close')) {
			checkoutModal.classList.remove('open');
		}
	});

	// Initialize cart display on page load
	updateCartDisplay();

})();
