# AppResto — Manuel d'installation et jeu de test (Lot 3)

> Ce README décrit l'installation locale, la configuration et le jeu de test du projet.

## 1. Branches / releases
- Branche à utiliser pour le développement : `main` (branche actuelle du dépôt).  
- Pour une release, exporter l'état de la branche `main` ou créer une archive ZIP de la branche.

## 2. Copie de l'application sur le serveur web
1. Installer XAMPP (Apache + MySQL/MariaDB) sur Windows.
2. Copier le dossier `PizApp/Lot 3` dans le répertoire public d'Apache (ex. `C:\xampp\htdocs\PizApp_Lot3`), ou placer l'ensemble du repo dans `C:\xampp\htdocs\` pour conserver la même structure.
3. Vérifier que le répertoire est accessible depuis le navigateur :


## 3. Mise en place de la base de données
Les scripts SQL sont fournis dans `Lot 3/db/` :
- `db_pizapp.v4.sql` — dump avec contraintes, index, données d'exemple et auto-increment (recommandé).  
- `db_pizapp.sql` — script plus simple (contient aussi données initiales).  
- `Trigger.md` — description des triggers recommandés à créer pour calculs automatiques (ex. calcul `total_ht`, montant TTC selon type commande).

Étapes d'installation de la base :
1. Démarrer MySQL via le panneau XAMPP.
2. Ouvrir `phpMyAdmin` (http://localhost/phpmyadmin) ou utiliser la ligne de commande MySQL.
3. Créer une base nommée `db_pizapp` (ou un autre nom, mais mettre à jour `ConnexionBDD.php`).
4. Importer `db_pizapp.v4.sql` (préféré) : dans phpMyAdmin, sélectionnez la base puis `Importer` → choisir le fichier `Lot 3/db/db_pizapp.v4.sql` et exécuter.```
5. (Optionnel) Créer les triggers décrits dans `Lot 3/db/Trigger.md` si votre dump ne les inclut pas automatiquement. Le fichier `Trigger.md` contient les définitions SQL à exécuter via phpMyAdmin > SQL.

## 4. Paramétrage de l'application
- Fichier de configuration de connexion DB : `Lot 3/ConnexionBDD.php`.
- Par défaut il contient :

```php
$server = "localhost";
$username = "root";
$password = ""; 
$db = "db_pizapp";
// $conn = mysqli_connect(...)
```

Modifiez ces valeurs si votre serveur MySQL utilise un autre utilisateur/mot de passe ou si vous avez choisi un autre nom de base.

## 5. Démarrage et vérification
1. Assurez-vous qu'Apache et MySQL sont démarrés via XAMPP.
2. Ouvrez `http://localhost/<votre-chemin>/index.php`.
3. Pages importantes :
- `index.php` — page publique.
- `Connexion.php` / `CreateAccount.php` — gestion des comptes.
- `productlist.php` — liste des produits (nécessite authentification).

## 6. Manuel du jeu de test — comptes et données fournis

Données et comptes fournis par les scripts SQL (`db_pizapp.v4.sql` et `db_pizapp.sql`) :

- Table `produit` : plusieurs dizaines de produits (pizzas, boissons, desserts) préchargés.  
  - Exemple d'entrées : `MARGHERITA`, `ROMAINE`, `REINE`, `Pepsi`, `Coca-cola`, `Crème brûlée`.
- Table `etat` : états des commandes (1..8) déjà insérés (ex. `initialisée`, `en préparation`, `prête`).
- Table `commande` et `ligne_de_commande` : le dump `db_pizapp.v4.sql` inclut au moins une commande d'exemple (idcommande = 1) avec lignes associées.

Comptes utilisateurs présents (dans `utilisateur`) :
- `iduser = 1` — `login_utilisateur`: `lasv_lya` — `email`: `LLaa@gmail.com`  
- `iduser = 2` — `login_utilisateur`: `TONON_Raphael` — `email`: `raphael.tonon@gmail.com`  

Remarque importante : les champs `mot_de_passe_utilisateur` stockent des hachages bcrypt (ex. `$2y$10$...`). Le dump n'expose pas les mots de passe en clair.

## 7. Tests fonctionnels rapides
- Parcours de test recommandé :
  1. Importer la base.
  2. Se connecter via `Connexion.php`.
  3. Aller sur `productlist.php`, ajouter plusieurs produits au panier (interaction front `scripts/cart.js` stockant `pizapp_cart` dans `localStorage`).
  4. Passer à la caisse et utiliser l'interface de paiement (les données de carte ne sont pas envoyées au serveur — la page envoie uniquement le `cart` et `type_commande` à `create_commande.php`).
  5. Vérifier dans la base que `commande` et `ligne_de_commande` ont été créées.

## 8. Fichiers importants contenus dans `Lot 3/` (résumé)
- `ConnexionBDD.php` — connexion MySQL (paramètres à modifier).  
- `index.php`, `Connexion.php`, `CreateAccount.php`, `Deconnexion.php` — pages publiques/authentification.
- `navbar.php` — rendu de la barre de navigation (utilise `$_SESSION['user_id']`).
- `productlist.php`, `productcard.php`, `products.php` — catalogue et rendu des produits; `products.php` utilise `ConnexionBDD.php`.
- `create_commande.php` — API server-side pour créer une commande (attend JSON POST { cart, type_commande }).
- `cart.php` — panneau cart côté serveur (HTML partiel).  
- `scripts/` — frontend JS : `cart.js` (localStorage/cart flow), `main.js`, `form-helpers.js`, `product-filters.js`.
- `css/styles.css` — styles.
- `db/` — SQL dumps et triggers description.

## 9. Remarques de sécurité et bonnes pratiques
- Les mots de passe sont hachés (bcrypt). Ne jamais stocker de mots de passe en clair.  
- `create_commande.php` recalcule les totaux côté serveur (bonne pratique) — conservez ce comportement lorsque vous modifiez la logique de création de commande.  
- Si vous mettez en production, configurez un utilisateur MySQL avec un mot de passe fort et limitez les permissions.

## 10. Diagrammes et maquettes (Lot 1)

Les livrables de conception sont fournis dans le dossier `Lot 1/` :
- `Diagramme de cas d'utilisation (Projets appResto, Raphaël et Lya)` — diagramme Draw.io (XML) décrivant les acteurs et cas d'utilisation ; 
- `Diagramme d'activité (Projets appResto, Raphaël et Lya)` — diagramme d'activité (format Draw.io).
- `AP1.SLAM(MCD,Lot1).loo` et `AP1.SLAM(MLD,Lot1).loo` — sources de modélisation (format binaire fourni).
- `Appresto(IHM).pdf` et `image.png` — maquettes d'interface (export Figma / PDF). Ouvrir le PDF pour visualiser les écrans.

Résumé (extrait du diagramme de cas d'utilisation) :
- Acteurs : `Client`, `Restaurateur`.
- Cas d'utilisation principaux pour le client : `Inscription`, `Connexion`, `Afficher la liste des produits`, `Saisir les quantités` (ajout au panier), `Choisir sur place / à emporter`, `Paiement (fictif)`.
- Cas d'utilisation pour le restaurateur : `Préparer la commande`, `Notifier le client`, `Accepter ou refuser la commande`.

## 11. Modèle conceptuel des données (MCD)
Le MCD (entités et associations) peut être dérivé directement des diagrammes et des tables SQL fournis. Les entités principales sont :

- `Utilisateur` — `iduser`, `login_utilisateur`, `email_utilisateur`, `mot_de_passe_utilisateur`.
- `Produit` — `idproduit`, `typeproduit`, `nomproduit`, `libproduit`, `prixproduit`, `imgproduit`.
- `Commande` — `idcommande`, `date_heure_commande`, `montant_ttc`, `type_commande`, `iduser`, `idetat`.
- `Ligne_de_commande` — `idcommande`, `idproduit`, `quantite`, `total_ht`.
- `Etat` — `idetat`, `libetat`, `description`.

Relations clés :
- `Utilisateur` 1 — N `Commande`.
- `Commande` 1 — N `Ligne_de_commande`.
- `Ligne_de_commande` N — 1 `Produit`.
- `Commande` N — 1 `Etat`.

Pour obtenir le diagramme visuel, ouvrir `AP1.SLAM(MCD,Lot1).loo` dans l'outil d'origine ou générer un export depuis phpMyAdmin après import.

## 12. Modèle logique des données (MLD)
Le MLD correspond à la représentation relationnelle (types, clés, contraintes) présente dans `db_pizapp.v4.sql` :

- Types : `int`, `varchar`, `text`, `double`/`decimal` pour montants.
- PK/Index : `idproduit`, `iduser`, `idcommande`, `idetat`.
- FK : `commande.iduser` → `utilisateur.iduser`, `ligne_de_commande.idproduit` → `produit.idproduit`, `ligne_de_commande.idcommande` → `commande.idcommande`.

Importer le script `db_pizapp.v4.sql` dans phpMyAdmin permet d'utiliser l'outil "Designer" pour visualiser le MLD.

## 13. Modèle physique des données (scripts SQL)
Le modèle physique est fourni par les scripts SQL dans `Lot 3/db/` :
- `db_pizapp.v4.sql` (recommandé) — structure + données + contraintes.
- `db_pizapp.sql` — alternative plus légère.
- `Trigger.md` — description et SQL des triggers (calcul `total_ht`, recalcul montant TTC selon `type_commande`).

Exécuter ces scripts installe les tables, les données d'exemple et les contraintes.

## 14. Valeurs possibles et conventions

- États des commandes (`etat`) — valeurs définies par les scripts :
  - `1` : `initialisée`
  - `2` : `finalisée`
  - `3` : `calculée`
  - `4` : `en attente`
  - `5` : `abandonnée`
  - `6` : `en préparation`
  - `7` : `prête`
  - `8` : `servie`

- Types de consommation (`type_commande`) :
  - `1` = `à emporter` (TVA 5.5% dans `Trigger.md`)
  - `0` = `sur place` (TVA 10% dans `Trigger.md`)

Ces conventions sont utilisées dans `create_commande.php` et par les triggers.

## 15. Sitemap (enchaînement des pages)
Parcours utilisateur principal :

- `index.php` → (`Connexion.php` | `CreateAccount.php`) → connexion
- après connexion → `productlist.php` (catalogue)
  - composant `productcard.php` pour chaque produit
  - panneau `cart.php` (inclus globalement)
  - scripts frontend : `scripts/cart.js` (localStorage `pizapp_cart`)
- checkout : `create_commande.php` (POST JSON) → `commande_valide.php`
- autres pages : `Checkout.php`, `Deconnexion.php`.

## 16. Maquettes IHM (Lot 1)
- Voir `Lot 1/Appresto(IHM).pdf` pour la maquette complète (export PDF).  
- Les sources de diagrammes et maquettes se trouvent dans `Lot 1/` (`.loo` et XML draw.io). Ouvrez-les avec draw.io, LibreOffice Draw, ou l'outil d'édition approprié.

## 18. Manuel d'utilisation (Lot 3)

Cette section décrit pas-à-pas les actions utilisateur courantes implémentées dans `Lot 3/`.

### Inscription
- Page : `CreateAccount.php`  
- Description : formulaire côté serveur pour créer un nouveau compte. Les champs typiques incluent `login`, `email`, `mot_de_passe` ; le mot de passe doit être haché avant insertion (la page utilise `password_hash()` ou équivalent si présent).  
- Étapes utilisateur :
  1. Ouvrir `CreateAccount.php` dans le navigateur.
  2. Remplir `login`, `email` et `mot de passe` puis soumettre.
  3. Après succès, l'utilisateur est généralement redirigé vers `Connexion.php` (comportement dépend de l'implémentation actuelle de `CreateAccount.php`).

### Connexion
- Page : `Connexion.php`  
- Description : authentification basée sur `login` et `mot_de_passe` ; la comparaison doit utiliser `password_verify()` pour vérifier le hachage stocké dans `utilisateur.mot_de_passe_utilisateur`.
- Étapes utilisateur :
  1. Ouvrir `Connexion.php`.
  2. Saisir `login` et `mot de passe` puis soumettre.
  3. Si l'authentification réussit : `$_SESSION['user_id']` et `$_SESSION['user_login']` sont définis, puis redirection vers `productlist.php`.
  4. Si échec : le code affiche une erreur et renvoie à la page de connexion.

### Commande (sélection et envoi)
- Pages/composants impliqués : `productlist.php`, `productcard.php`, `cart.php`, `scripts/cart.js`, `create_commande.php`.
- Description : le client parcourt la liste des produits (rendu par `productcard.php`) et ajoute des items au panier. Le panier est stocké côté client dans `localStorage` sous la clé `pizapp_cart` et synchronisé lors de la validation.
- Étapes utilisateur :
  1. Se rendre sur `productlist.php` (requiert authentification).
  2. Cliquer sur les boutons d'ajout (les boutons portent les attributs `data-product-id`, `data-product-title`, `data-product-price`).
  3. Ouvrir le panneau panier (icône `cart-toggle`) pour vérifier/ajuster quantités ou supprimer des items.
  4. Cliquer sur `Passer à la caisse` pour ouvrir le panneau de paiement (`checkout-panel`).
  5. Confirmer la commande : le front envoie une requête `POST` JSON vers `create_commande.php` avec le corps :

```json
{
  "cart": [{ "id": 12, "quantity": 2 }, { "id": 31, "quantity": 1 }],
  "type_commande": 1
}
```

  6. `create_commande.php` : vérifie la session, récupère les prix depuis la base, calcule le total, crée la `commande` et les `ligne_de_commande`, puis renvoie JSON `{ success: true, idcommande: <id> }`.
  7. En cas de succès, le front redirige vers `commande_valide.php?id=<idcommande>`.

### Paiement (interface et notes)
- Interface : panneau de paiement présent dans le DOM (rendu par `cart.php`) et contrôlé par `scripts/cart.js`.
- Comportement actuel : paiement fictif — le formulaire collecte des champs de paiement (nom, numéro, expiry, CVV) mais NE transmet PAS ces données au serveur. Seule la commande (liste d'items + `type_commande`) est envoyée à `create_commande.php` pour créer la commande côté serveur.

Notes supplémentaires :
- `create_commande.php` recalcule les montants côté serveur en utilisant les prix en base — le serveur ne fait pas confiance aux prix envoyés par le client.
- Assurez-vous d'être connecté avant de passer la commande (sinon `productlist.php` redirige vers `Connexion.php`).

---