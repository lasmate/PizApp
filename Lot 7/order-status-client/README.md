# Order Status Client (Java Swing)

Base de client desktop Java Swing pour le projet PizApp.

## Objectif
- Afficher les commandes de la table `commande` (avec client + statut).
- Mettre à jour le statut d'une ou plusieurs commandes depuis l'interface.

## Interface
- Écran principal unique en **dark mode** avec style Material-like (texte et boutons agrandis).
- Tableau des commandes avec colonne **Sélection** (checkbox) pour choisir plusieurs commandes.
- Action **Mettre à jour le statut** : applique le statut choisi aux commandes cochées (ou à la ligne sélectionnée si aucune case n'est cochée).
- Action **Ouvrir les détails sélectionnés** : ouvre une fenêtre de détail par commande cochée.
- Chaque fenêtre de détail affiche les infos + lignes de commande et propose les actions **Accepter**, **Refuser**, **Marquer prête**, **Rafraîchir**.

## Pré-requis
- Java 8+
- Maven 3.9+
- Base MySQL/MariaDB `db_pizapp` accessible

## Configuration
Modifier `src/main/resources/db.properties` :

```properties
db.url=jdbc:mysql://localhost:3306/db_pizapp?useSSL=false&allowPublicKeyRetrieval=true&serverTimezone=UTC
db.user=root
db.password=
```

## Lancer
Depuis ce dossier :

```bash
mvn clean compile
mvn exec:java
```

## Statuts intégrés dans le client
Le client propose ces transitions manuelles :
- `5` : abandonnée
- `6` : en préparation
- `7` : prête
- `8` : servie

(Conforme aux valeurs de `etat` dans la base du projet.)

### Mapping des boutons (fenêtres de détail)
- **Accepter** ⟶ `idetat = 4` (même mapping que `api/commande_accepter.php` actuel)
- **Refuser** ⟶ `idetat = 5`
- **Marquer prête** ⟶ `idetat = 7`
