/**
 * Cart Modal Management
 * - Hidden on load
 * - Small toggle button inside HeroCard bottom-right
 * - On click: button rolls, modal slides from button position to center diagonally
 */

(function () {
	const hero = document.querySelector('.HeroCard');
	const modal = document.querySelector('.cart-modal');
	const backdrop = document.querySelector('.cart-backdrop');
	const toggleBtn = document.querySelector('.cart-toggle');
	const closeBtn = document.querySelector('.cart-close');

	if (!hero || !modal || !backdrop || !toggleBtn) return;

	// Ensure closed on load
	modal.classList.remove('open');
	backdrop.classList.remove('open');

	function openCart() {
		// Trigger roll animation on the button
		toggleBtn.classList.add('roll');
		// Start states are already defined in CSS; just flip to open state
		backdrop.classList.add('open');
		modal.classList.add('open');
		// Remove the roll class after animation ends
		setTimeout(() => toggleBtn.classList.remove('roll'), 450);
	}

	function closeCart() {
		backdrop.classList.remove('open');
		modal.classList.remove('open');
	}

	toggleBtn.addEventListener('click', () => {
		const isOpen = modal.classList.contains('open');
		if (isOpen) closeCart(); else openCart();
	});

	backdrop.addEventListener('click', closeCart);
	if (closeBtn) closeBtn.addEventListener('click', closeCart);
})();