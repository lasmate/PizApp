/**
 * Form Helper Functions
 * Handles form input interactions and enhancements
 */

(function () {
	/**
	 * Make input icons clickable to clear their associated form fields
	 */
	function initClearInputIcons() {
		const inputIcons = document.querySelectorAll('.input-icon');
		
		inputIcons.forEach(icon => {
			icon.addEventListener('click', function() {
				// Find the associated input field (sibling in the same .input-box)
				const inputBox = this.closest('.input-box');
				if (inputBox) {
					const inputField = inputBox.querySelector('input');
					if (inputField) {
						inputField.value = '';
						inputField.focus(); // Optional: focus the field after clearing
					}
				}
			});
		});
	}

	/**
	 * Initialize all form helpers when DOM is loaded
	 */
	function initFormHelpers() {
		initClearInputIcons();
	}

	// Initialize when DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initFormHelpers);
	} else {
		initFormHelpers();
	}
});