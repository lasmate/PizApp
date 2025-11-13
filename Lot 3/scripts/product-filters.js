/**
 * product-filters.js
 * Simple client-side filter for product cards using the sidebar buttons.
 * It toggles the CSS display property of `.product-card` elements based on their
 * `data-product-type` attribute.
 */

document.addEventListener('DOMContentLoaded', function () {
    const buttons = Array.from(document.querySelectorAll('.sidebar-btn'));

    if (!buttons.length) return; // nothing to do

    const cards = () => Array.from(document.querySelectorAll('.product-card'));

    // Normalize text for simple matching (lowercase, remove diacritics)
    function normalize(text) {
        return (text || '').toString().toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '').trim();
    }

    function applyFilter(filter) {
        const normFilter = normalize(filter);
        // Show all if filter indicates 'carte' or 'all' or empty
        const showAll = normFilter === 'carte' || normFilter === 'all' || normFilter === '';

        cards().forEach(card => {
            const type = normalize(card.dataset.productType || '');

            let visible = true;
            if (!showAll) {
                // Match if the card's type contains the filter text or vice-versa.
                visible = (type && (type.indexOf(normFilter) !== -1 || normFilter.indexOf(type) !== -1));
            }

            card.style.display = visible ? '' : 'none';
        });
    }

    // Hook up buttons
    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            // toggle active class
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Use data-filter if provided, otherwise button text
            const filter = this.dataset.filter ? this.dataset.filter : this.textContent;
            applyFilter(filter);
        });
    });

    // Apply initial filter based on the button that already has .active
    const initial = buttons.find(b => b.classList.contains('active'));
    if (initial) {
        const filter = initial.dataset.filter ? initial.dataset.filter : initial.textContent;
        applyFilter(filter);
    }
});
