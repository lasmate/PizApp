/**
 * Script principal de l'application
 * Initialisation centrale et utilitaires globaux
 */

(function () {
	'use strict';

	/**
	 * Configuration globale de l'application
	 */
	const AppConfig = {
		debug: false,
		version: '1.0.0'
	};

	/**
	 * Fonctions utilitaires
	 */
	const Utils = {
		/**
		 * Journalise les messages lorsque le mode debug est activé
		 * @param {string} message - Message à logger
		 * @param {*} data - Données optionnelles à afficher
		 */
		log: function(message, data) {
			if (AppConfig.debug) {
				console.log(`[PizApp] ${message}`, data || '');
			}
		},

		/**
		 * Vérifie si un élément existe dans le DOM
		 * @param {string} selector - Sélecteur CSS
		 * @returns {boolean}
		 */
		elementExists: function(selector) {
			return document.querySelector(selector) !== null;
		},

		/**
		 * Exécution sûre au ready du DOM
		 * @param {Function} callback - Fonction à exécuter
		 */
		ready: function(callback) {
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', callback);
			} else {
				callback();
			}
		}
	};

	/**
	 * Initialise l'application
	 */
	function initApp() {
		Utils.log('Application initialized');
		
	}

	// Make utilities available globally if needed
	window.PizApp = {
		Utils: Utils,
		Config: AppConfig
	};

	// Initialize app when ready
	Utils.ready(initApp);
})();