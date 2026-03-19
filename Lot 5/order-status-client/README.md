# Order Status Client (Java Swing)

Base de client desktop Java Swing pour le projet PizApp.

## Objectif
- Afficher les commandes de la table `commande` (avec client + statut).
- Mettre à jour le statut d'une commande depuis l'interface.

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
