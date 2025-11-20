/**
 * Fonctions d'aide aux formulaires
 * Gère les interactions et améliorations des champs de formulaire
 */

(function () {
	/**
	 * Rend les icônes des champs cliquables pour effacer le champ associé
	 */
	function initClearInputIcons() {
		const inputIcons = document.querySelectorAll('.input-icon');
		
		inputIcons.forEach(icon => {
			icon.addEventListener('click', function() {
				// Trouve le champ input associé (frère dans la même .input-box)
				const inputBox = this.closest('.input-box');
				if (inputBox) {
					const inputField = inputBox.querySelector('input');
					if (inputField) {
						inputField.value = '';
						inputField.focus(); // Optionnel : positionne le focus après effacement
					}
				}
			});
		});
	}

	/**
	 * Initialise tous les helpers de formulaire lorsque le DOM est chargé
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