/**
 * Cart Panel Management
 * Handles the diagonal sliding cart panel functionality
 */

(function () {
	const cartPanel = document.querySelector('.cart-panel');
	const toggleBtn = document.querySelector('.cart-toggle');

	if (!cartPanel || !toggleBtn) return;

	// Ensure initial state has CSS applied once DOM is ready
	// The HTML sets cart-panel as open by default; you can change as needed.

	function toggleCart(forceState) {
		const isOpen = cartPanel.classList.contains('open');
		const nextOpen = typeof forceState === 'boolean' ? forceState : !isOpen;
		cartPanel.classList.toggle('open', nextOpen);
	}

	toggleBtn.addEventListener('click', () => toggleCart());

	// Optional: close when clicking outside the panel
	document.addEventListener('click', (e) => {
		if (!cartPanel.classList.contains('open')) return;
		const isClickInside = cartPanel.contains(e.target) || toggleBtn.contains(e.target);
		if (!isClickInside) toggleCart(false);
	});
})();