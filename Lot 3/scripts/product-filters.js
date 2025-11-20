/**
 * product-filters.js
 * Filtre client simple pour les cartes produits depuis le menu latéral.
 * Il bascule la propriété CSS `display` des éléments `.product-card` en fonction
 * de l'attribut `data-product-type`.
 */

document.addEventListener('DOMContentLoaded', function () {
    const buttons = Array.from(document.querySelectorAll('.sidebar-btn'));

    if (!buttons.length) return; // rien à faire

    const cards = () => Array.from(document.querySelectorAll('.product-card'));

    // Normalise le texte pour une correspondance simple (minuscules, suppression des diacritiques)
    function normalize(text) {
        return (text || '').toString().toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '').trim();
    }

    function applyFilter(filter) {
        const normFilter = normalize(filter);
        // Affiche tout si le filtre indique 'carte', 'all' ou est vide
        const showAll = normFilter === 'carte' || normFilter === 'all' || normFilter === '';

        cards().forEach(card => {
            const type = normalize(card.dataset.productType || '');

            let visible = true;
            if (!showAll) {
                // Correspond si le type de la carte contient le texte du filtre ou inversement.
                visible = (type && (type.indexOf(normFilter) !== -1 || normFilter.indexOf(type) !== -1));
            }

            card.style.display = visible ? '' : 'none';
        });
    }

    // Connecte les boutons
    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            // bascule la classe active
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Utilise data-filter si présent, sinon le texte du bouton
            const filter = this.dataset.filter ? this.dataset.filter : this.textContent;
            applyFilter(filter);
        });
    });

    // Applique le filtre initial basé sur le bouton qui a déjà la classe .active
    const initial = buttons.find(b => b.classList.contains('active'));
    if (initial) {
        const filter = initial.dataset.filter ? initial.dataset.filter : initial.textContent;
        applyFilter(filter);
    }
});
