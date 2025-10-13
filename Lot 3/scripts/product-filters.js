/**
 * Product Filters Management
 * Handles filtering of products by category
 */

(function () {
    'use strict';

    /**
     * Initialize product filters functionality
     */
    function initProductFilters() {
        const filterButtons = document.querySelectorAll('.sidebar-btn');
        const productCards = document.querySelectorAll('.product-card');

        if (!filterButtons.length || !productCards.length) return;

        // Add click event to each filter button
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get filter category
                const category = this.textContent.trim().toLowerCase();
                
                // Filter products
                filterProducts(category, productCards);
            });
        });
    }

    /**
     * Filter products based on category
     * @param {string} category - Category to filter by
     * @param {NodeList} productCards - All product cards
     */
    function filterProducts(category, productCards) {
        productCards.forEach(card => {
            const title = card.querySelector('.product-title').textContent.toLowerCase();
            let shouldShow = true;

            switch(category) {
                case 'menu':
                    // Show all products
                    shouldShow = true;
                    break;
                case 'pizza':
                    shouldShow = title.includes('pizza');
                    break;
                case 'boissons':
                    shouldShow = title.includes('coca') || title.includes('eau') || 
                               title.includes('boisson') || title.includes('drink');
                    break;
                case 'desserts':
                    shouldShow = title.includes('tiramisu') || title.includes('panna') || 
                               title.includes('dessert') || title.includes('gâteau');
                    break;
                default:
                    shouldShow = true;
            }

            // Apply filter with smooth animation
            if (shouldShow) {
                card.style.display = 'flex';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    if (card.style.opacity === '0') {
                        card.style.display = 'none';
                    }
                }, 200);
            }
        });
    }

    /**
     * Add smooth transitions to product cards
     */
    function addProductTransitions() {
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        });
    }

    /**
     * Initialize when DOM is ready
     */
    function init() {
        initProductFilters();
        addProductTransitions();
        
        if (window.PizApp && window.PizApp.Utils) {
            window.PizApp.Utils.log('Product filters initialized');
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();