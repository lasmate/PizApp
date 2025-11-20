(function(){
  'use strict';

  const STORAGE_KEY = 'pizapp_cart';

  const $ = (sel, root=document) => root.querySelector(sel);
  const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));

  function loadCart(){
    try{
      const raw = localStorage.getItem(STORAGE_KEY);
      if(!raw) return [];
      const arr = JSON.parse(raw);
      if(Array.isArray(arr)) return arr;
      return [];
    }catch(e){
      return [];
    }
  }

  function saveCart(cart){
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
    updateBadge(cart);
  }


// Ajouter un article ou augmenter la quantité si déjà présent dans le panier
  function addItem({id, title, price}){
    const cart = loadCart();
    const pid = parseInt(id, 10);
    const p = parseFloat(price || '0') || 0;
    const found = cart.find(it => it.id === pid);// vérifie si l'article est déjà dans le panier
    if(found){
      found.quantity += 1;
    } else {
      cart.push({ id: pid, title: title || 'Produit', price: p, quantity: 1 });
    }
    saveCart(cart);
    renderCart();
  }
// supprimer un article du panier par id
  function removeItem(id){
    const cart = loadCart().filter(it => it.id !== id);
    saveCart(cart);
    renderCart();
  }

  function changeQty(id, delta){
    const cart = loadCart();
    const it = cart.find(i=> i.id === id);
    if(!it) return;
    it.quantity = Math.max(1, it.quantity + delta);
    saveCart(cart);
    renderCart();
  }

  function clearCart(){
    saveCart([]);
    renderCart();
  }

  function formatEuro(n){
    const v = (typeof n === 'number' ? n : parseFloat(n||'0')||0);
    return v.toFixed(2) + '€';
  }

  function calcTotal(cart){
    return cart.reduce((sum, it)=> sum + (it.price||0) * (it.quantity||1), 0);
  }

  function updateBadge(cart){
    const badge = $('#cart-count-badge');
    if(!badge) return;
    const count = (cart||loadCart()).reduce((n, it)=> n + (it.quantity||1), 0);
    badge.textContent = count;
  }

  function renderCart(){// Rend les éléments du panier dans le panneau panier
    const container = $('#cart-items');
    const totalEl = $('#cart-total');
    if(!container || !totalEl) return;

    const cart = loadCart();
    container.innerHTML = '';

    if(cart.length === 0){
      container.innerHTML = '<p class="empty-checkout-message">Votre panier est vide.</p>';
      totalEl.textContent = '0.00€';
      updateBadge(cart);
      return;
    }

    cart.forEach(item => {
      const row = document.createElement('div');
      row.className = 'cart-item';
      row.dataset.id = String(item.id);
      row.innerHTML = `
        <div class="cart-item-title">${escapeHtml(item.title)}</div>
        <div class="item-subtotal">${formatEuro((item.price||0) * (item.quantity||1))}</div>
        <div class="cart-item-actions">
          <div class="cart-item-sub">${formatEuro(item.price)} / unité</div>
          <div class="qty-controls" role="group" aria-label="Quantité">
            <button class="qty-btn" data-action="dec" aria-label="Diminuer">-</button>
            <span class="qty-value">${item.quantity}</span>
            <button class="qty-btn" data-action="inc" aria-label="Augmenter">+</button>
          </div>
          <button class="item-remove" title="Retirer">Retirer</button>
        </div>`;
      container.appendChild(row);
    });

    totalEl.textContent = formatEuro(calcTotal(cart));
    updateBadge(cart);
  }

  function escapeHtml(str){// fonction pour prévenir les attaques XSS en échappant les caractères HTML spéciaux
    return String(str||'').replace(/[&<>"]+/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[s]));
  }

  function openPanel(){
    const panel = document.querySelector('.cart-panel');
    if(panel){ panel.classList.add('open'); panel.setAttribute('aria-hidden','false'); }
  }
  function closePanel(){
    const panel = document.querySelector('.cart-panel');
    if(panel){ panel.classList.remove('open'); panel.setAttribute('aria-hidden','true'); }
  }
  function togglePanel(){
    const panel = document.querySelector('.cart-panel');
    if(!panel) return;
    panel.classList.toggle('open');
    panel.setAttribute('aria-hidden', panel.classList.contains('open') ? 'false' : 'true');
  }

  function attachEvents(){
    // Bascule l'ouverture depuis la poignée demi-cercle
    const toggle = document.querySelector('.cart-toggle');
    if(toggle){ toggle.addEventListener('click', togglePanel); }

    // Bouton de fermeture dans le panneau
    const closeBtn = document.querySelector('.cart-close');
    if(closeBtn){ closeBtn.addEventListener('click', closePanel); }

    // Boutons 'Ajouter au panier' dans le catalogue
    document.addEventListener('click', (e)=>{
      const btn = e.target.closest('.add-to-cart');
      if(!btn) return;
      const id = btn.getAttribute('data-product-id');
      const title = btn.getAttribute('data-product-title');
      const price = btn.getAttribute('data-product-price');
      addItem({id, title, price});
    });

    // Contrôles de quantité et suppression (délégation d'événements)
    const list = document.querySelector('#cart-items');
    if(list){
      list.addEventListener('click', (e)=>{
        const row = e.target.closest('.cart-item');
        if(!row) return;
        const id = parseInt(row.dataset.id, 10);
        const t = e.target;
        if(t.matches('.qty-btn')){
          const action = t.getAttribute('data-action');
          changeQty(id, action === 'inc' ? 1 : -1);
        } else if (t.matches('.item-remove')){
          removeItem(id);
        }
      });
    }

    // Vider le panier
    const clear = document.querySelector('#cart-clear');
    if(clear){ clear.addEventListener('click', clearCart); }

    // Ouvre la superposition de paiement
    const checkout = document.querySelector('#cart-checkout');
    if(checkout){
      checkout.addEventListener('click', (e)=>{
        e.preventDefault();
        const cart = loadCart();
        if(cart.length === 0){ openPanel(); return; }
        const pane = document.querySelector('.checkout-panel');
        if(pane){ pane.classList.add('open'); pane.setAttribute('aria-hidden','false'); }
      });
    }

    // Ferme la superposition de paiement
    const closeCheckout = document.querySelector('.checkout-close');
    if(closeCheckout){
      closeCheckout.addEventListener('click', ()=>{
        const pane = document.querySelector('.checkout-panel');
        if(pane){ pane.classList.remove('open'); pane.setAttribute('aria-hidden','true'); }
      });
    }

    // Retour au panier depuis le paiement
    const backBtn = document.querySelector('#checkout-back');
    if(backBtn){
      backBtn.addEventListener('click', ()=>{
        const pane = document.querySelector('.checkout-panel');
        if(pane){ pane.classList.remove('open'); pane.setAttribute('aria-hidden','true'); }
      });
    }

    // Bascule la sélection du type de service
    const chips = $$('.service-chip');
    if(chips.length){
      chips.forEach(ch => ch.addEventListener('click', ()=>{
        chips.forEach(c => c.classList.remove('active'));
        ch.classList.add('active');
      }));
    }

    // Confirmer le paiement -> POST avec type_commande
    const confirmBtn = document.querySelector('#confirm-payment');
    if(confirmBtn){
      confirmBtn.addEventListener('click', async ()=>{
        const cart = loadCart().map(({id, quantity}) => ({id, quantity}));
        if(cart.length === 0){ return; }

        // Récupère type_commande depuis la puce active (par défaut 1 = à emporter)
        const activeChip = document.querySelector('.service-chip.active');
        const type_commande = activeChip ? parseInt(activeChip.getAttribute('data-type'), 10) : 1;

        // Validation minimale côté client - ne pas envoyer les données de carte au serveur
        const name = (document.getElementById('card-name')||{}).value || '';
        const number = (document.getElementById('card-number')||{}).value || '';
        const exp = (document.getElementById('card-exp')||{}).value || '';
        const cvv = (document.getElementById('card-cvv')||{}).value || '';
        if(!name || !number || !exp || !cvv){
          alert('Veuillez renseigner les informations de paiement.');
          return;
        }

        try{
          const res = await fetch('create_commande.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart, type_commande })
          });
          const data = await res.json();
          if(data && data.success){
            clearCart();
            window.location.href = `commande_valide.php?id=${data.idcommande}`;
          } else {
            alert(data && data.message ? data.message : 'Erreur lors de la création de la commande');
          }
        }catch(err){
          alert('Impossible de valider la commande.');
        }
      });
    }

    // Initialize from storage
    updateBadge();
    renderCart();
  }

  if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', attachEvents);
  } else {
    attachEvents();
  }
})();
