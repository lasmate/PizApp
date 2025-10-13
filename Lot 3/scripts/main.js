/**
 * Main Application Script
 * Central initialization and global utilities
 */

(function () {
	'use strict';

	/**
	 * Global app configuration
	 */
	const AppConfig = {
		debug: false,
		version: '1.0.0'
	};

	/**
	 * Utility functions
	 */
	const Utils = {
		/**
		 * Log messages when debug mode is enabled
		 * @param {string} message - Message to log
		 * @param {*} data - Optional data to log
		 */
		log: function(message, data) {
			if (AppConfig.debug) {
				console.log(`[PizApp] ${message}`, data || '');
			}
		},

		/**
		 * Check if element exists in DOM
		 * @param {string} selector - CSS selector
		 * @returns {boolean}
		 */
		elementExists: function(selector) {
			return document.querySelector(selector) !== null;
		},

		/**
		 * Safe DOM ready execution
		 * @param {Function} callback - Function to execute
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
	 * Initialize the application
	 */
	function initApp() {
		Utils.log('Application initialized');
		
		// Add any global event listeners or initializations here
		// For example: error handling, analytics, etc.
	}

	// Make utilities available globally if needed
	window.PizApp = {
		Utils: Utils,
		Config: AppConfig
	};

	// Initialize app when ready
	Utils.ready(initApp);
})();