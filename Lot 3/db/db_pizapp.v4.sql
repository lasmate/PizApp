-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 06 nov. 2025 à 10:35
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_pizapp`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `idcommande` int(25) NOT NULL COMMENT 'id de la commande',
  `date_heure_commande` datetime(6) DEFAULT NULL COMMENT 'date et heure de la commande',
  `montant_ttc` decimal(5,2) DEFAULT NULL COMMENT 'montant tout taxe comprise de la commande',
  `type_commande` tinyint(1) DEFAULT NULL COMMENT 'le type de la commande',
  `iduser` int(25) NOT NULL COMMENT 'id de l''utilisateur',
  `idetat` int(25) NOT NULL COMMENT 'id de l''état'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`idcommande`, `date_heure_commande`, `montant_ttc`, `type_commande`, `iduser`, `idetat`) VALUES
(1, '2025-11-01 08:50:04.000000', 238.70, 0, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

CREATE TABLE `etat` (
  `idetat` int(25) NOT NULL COMMENT 'id de l''état de la commande',
  `libetat` text NOT NULL COMMENT 'description de l''état de la commande',
  `description` text DEFAULT NULL COMMENT 'description détaillée de l''état de la commande'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`idetat`, `libetat`, `description`) VALUES
(1, 'initialisée', 'le client est en train de choisir sa commande'),
(2, 'finalisée', 'le choix de la commande est terminé et validé'),
(3, 'calculée', 'le total de la commande est calculé et en attente de paiement'),
(4, 'en attente', 'la commande est payée et en attente de préparation par le restaurateur'),
(5, 'abandonnée', 'la commande est refusée par le restaurateur'),
(6, 'en préparation', 'la commande est acceptée et en cours de préparation par le restaurateur'),
(7, 'prête', 'la commande est prête et en attente d\'être récupérée par le client'),
(8, 'servie', 'la commande a été récupérée par le client');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_de_commande`
--

CREATE TABLE `ligne_de_commande` (
  `idcommande` int(25) NOT NULL COMMENT 'id de la commande',
  `idproduit` int(25) NOT NULL COMMENT 'id du produit commandé',
  `quantite` int(255) DEFAULT NULL COMMENT 'quantité du produit commandé',
  `total_ht` decimal(5,2) DEFAULT NULL COMMENT 'total des produit HT '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ligne_de_commande`
--

INSERT INTO `ligne_de_commande` (`idcommande`, `idproduit`, `quantite`, `total_ht`) VALUES
(1, 2, 2, 22.00),
(1, 3, 5, 57.50),
(1, 5, 5, 62.50),
(1, 6, 6, 75.00);

--
-- Déclencheurs `ligne_de_commande`
--


-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `idproduit` int(25) NOT NULL COMMENT 'id du produit',
  `typeproduit` enum('pizza','boisson','dessert','autre') DEFAULT 'autre' COMMENT 'type du produit',
  `nomproduit` varchar(255) NOT NULL,
  `libproduit` text DEFAULT NULL COMMENT 'description du produit',
  `prixproduit` double DEFAULT NULL COMMENT 'prix du produit',
  `imgproduit` varchar(255) DEFAULT NULL COMMENT 'Lien de l''image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`idproduit`, `typeproduit`, `nomproduit`, `libproduit`, `prixproduit`, `imgproduit`) VALUES
(1, 'pizza', 'MARGHERITA', 'tomate_fromage_olives_origan', 9.5, 'img/pizza_margherita.jpg'),
(2, 'pizza', 'ROMAINE', 'tomate_jambon_fromage_olives_origan', 11, 'img/pizza_romaine.jpg'),
(3, 'pizza', 'REINE', 'tomate_jambon_champignons_fromage_olives_origan', 11.5, 'img/pizza_reine.jpg'),
(4, 'pizza', 'FORESTIERE', 'tomate_champignons_persillade_crème_fromage_olives_origan', 11.5, 'img/pizza_forestiere.jpg'),
(5, 'pizza', 'CHORIZO', 'tomate_chorizo_poivrons_fromage_olives_origan', 12.5, 'img/pizza_chorizo.jpg'),
(6, 'pizza', 'ORIENTALE', 'tomate_merguez_poivrons_fromage_olives_origan', 12.5, 'img/pizza_orientale.jpg'),
(7, 'pizza', '4 FROMAGES', 'tomate_chèvre_roquefort_fromages_olives_origan', 12.5, 'img/pizza_4_fromages.jpg'),
(8, 'pizza', 'SEGUIN', 'tomate_jambon_chèvre_fromage_olives_origan', 12.5, 'img/pizza_seguin.jpg'),
(9, 'pizza', 'CALZONE', 'tomate_jambon_champignons_oeuf_fromage_olives_origan', 13, 'img/pizza_calzone.jpg'),
(10, 'pizza', 'BOLOGNAISE', 'tomate_viande-hachée_champignons_crème_fromage_olives_origan', 12.5, 'img/pizza_bolognaise.jpg'),
(11, 'pizza', 'MEXICAINE', 'tomate_viande-hachée-épicée_oignons_poivrons_maïs_fromage_olives_origan', 12.5, 'img/pizza_mexicaine.jpg'),
(12, 'pizza', 'POULET', 'tomate_poulet_champignons_crème_fromages_olives_origan', 12.5, 'img/pizza_poulet.jpg'),
(13, 'pizza', 'ANCHOIS', 'tomate_anchois_câpres_fromage_olives_origan', 12.5, 'img/pizza_anchois.jpg'),
(14, 'pizza', 'VEGETARIENNE', 'tomate_champignons_oignons_artichauts_poivrons_fromage_olives_origan', 12.5, 'img/pizza_vegetarienne.jpg'),
(15, 'pizza', 'ROQUEFORT', 'tomate_roquefort_jambon_fromage_olives_origan', 12, 'img/pizza_roquefort.jpg'),
(16, 'pizza', 'FRUITS DE MER', 'tomate_fruits_de_mer_persillade_fromage_olives_origan', 13.5, 'img/pizza_fruit_de_mer.jpg'),
(17, 'pizza', 'CAMPAGNARDE', 'tomate_lardons_oignons_oeuf_crème_fromage_olives_origan', 13, 'img/pizza_campagnarde.jpg'),
(18, 'pizza', 'PECHEUR', 'tomate_thon_persillade_crème_fromage_olives_origan', 12.5, 'img/pizza_pecheur.jpg'),
(19, 'pizza', 'HAWAIENNE', 'crème_jambon_ananas_curry_fromage_olives_origan', 12.5, 'img/pizza_hawaienne.jpg'),
(20, 'pizza', 'FLAMICHE', 'crème_lardons_oignons_fromage_olives_origan', 12.5, 'img/pizza_flamiche.jpg'),
(21, 'pizza', 'TARTIFLETTE', 'crème_lardons_pomme_de_terre_reblochon_fromage_olives', 13.5, 'img/pizza_tartiflette.jpg'),
(22, 'pizza', 'MONTAGNARDE', 'crème_diot_de_savoie_oignons_tomme_de_savoie_pomme_de_terre_fromage_olives_origan', 13.5, 'img/pizza_montagnarde.jpg'),
(23, 'pizza', 'RAVIOLE', 'crème_ravioles_fromage_olives_origan', 13.5, 'img/pizza_raviole.jpg'),
(24, 'pizza', 'RAVIOLE SAUMON', 'crème_ravioles_saumon_fromage_olives_origan', 14, 'img/pizza_raviole_saumon.jpg'),
(25, 'pizza', 'MURETINE', 'crème_st_marcellin_persillade_fromage_olives_origan', 13, 'img/pizza_muretine.jpg'),
(26, 'pizza', 'RACLETTE', 'crème_raclette_jambon_cru_pomme_de_terre_fromage_olives_origan', 13.5, 'img/pizza_raclette.jpg'),
(27, 'pizza', 'SAUMON', 'crème_persillade_saumon_fromage_olives_origan', 13.5, 'img/pizza_saumon.jpg'),
(28, 'pizza', 'GRENOBLOISE', 'crème_chèvre_miel_noix_fromage_olives_origan', 13, 'img/pizza_grenobloise.jpg'),
(29, 'pizza', 'KEBAB', 'crème_viande_kebab_sauce_kebab_tomate_fraîche_fromage_olives_origan', 13, 'img/pizza_kebab.jpg'),
(30, 'pizza', 'PIZZA SUCREE', 'crème_nutella_spéculoos_ou_noix_de_coco', 9.5, 'img/pizza_pizza_sucrée.jpg'),
(31, 'boisson', 'Pepsi', 'Boisson iconique au goût frais et intense, c\'est une boisson gazeuse délicieuse et rafraîchissante aux extraits végétaux', 2, 'img/boisson_pepsi.jpg'),
(32, 'boisson', 'Pepsi MAX', 'La version sans sucre du Pepsi, tout le plaisir sans les calories, toujours aussi rafraîchissant.', 2, 'img/boisson_pepsi_max.jpg'),
(33, 'boisson', 'KAS', 'Boisson pétillante aux saveurs fruitées, idéale pour une pause fraîcheur.', 1.8, 'img/boisson_kas.jpg'),
(34, 'boisson', 'Lipton', 'Thé glacé désaltérant, parfait pour se rafraîchir à tout moment de la journée.', 2, 'img/boisson_lipton.jpg'),
(35, 'boisson', 'Oasis', 'Boisson aux fruits sans bulles, douce et fruitée, pour petits et grands.', 2, 'img/boisson_oasis.jpg'),
(36, 'boisson', 'Coca-cola', 'La boisson gazeuse la plus célèbre au monde, goût unique et rafraîchissant.', 2, 'img/boisson_coca-cola.jpg'),
(37, 'boisson', 'Fanta', 'Boisson pétillante à l’orange, fun et fruitée, pour une explosion de saveurs.', 1.8, 'img/boisson_fanta.jpg'),
(38, 'boisson', 'Sprite', 'Boisson gazeuse citron-lime, ultra rafraîchissante et désaltérante.', 1.8, 'img/boisson_sprite.jpg'),
(39, 'boisson', 'Nestea', 'Thé glacé au goût subtil, parfait pour une pause fraîcheur.', 2, 'img/boisson_nestea.jpg'),
(40, 'boisson', 'Minute Maid', 'Jus de fruits savoureux, 100% plaisir, 100% fruits.', 2, 'img/boisson_minute_maid.jpg'),
(41, 'boisson', 'Orangina', 'Boisson pétillante à l’orange, pulpeuse et pleine de peps.', 2, 'img/boisson_orangina.jpg'),
(42, 'boisson', 'Schweppes', 'Boisson gazeuse au goût amer unique, idéale pour l’apéritif.', 2, 'img/boisson_schweppes.jpg'),
(43, 'boisson', 'Ricqles', 'Menthe forte et rafraîchissante, pour un coup de frais instantané.', 2.2, 'img/boisson_ricqles.jpg'),
(44, 'boisson', 'Perrier', 'Eau minérale gazeuse naturelle, bulles fines et rafraîchissantes.', 1.5, 'img/boisson_perrier.jpg'),
(45, 'boisson', 'Pschitt', 'Boisson pétillante au citron ou à l’orange, rétro et désaltérante.', 1.8, 'img/boisson_pschitt.jpg'),
(46, 'boisson', 'Cacolac', 'Boisson lactée au cacao, douceur et gourmandise à l’état pur.', 2, 'img/boisson_cacolac.jpg'),
(47, 'boisson', 'Granini', 'Jus de fruits premium, saveur intense et naturelle.', 2.2, 'img/boisson_granini.jpg'),
(48, 'boisson', 'Joker', 'Jus de fruits variés, douceur et vitamines pour toute la famille.', 2, 'img/boisson_joker.jpg'),
(49, 'boisson', 'Red Bull', 'Boisson énergisante, donne des ailes pour affronter la journée.', 2.5, 'img/boisson_red_bull.jpg'),
(50, 'boisson', 'Monster', 'Boisson énergisante au goût puissant, pour un maximum d’énergie.', 2.5, 'img/boisson_monster.jpg'),
(51, 'dessert', 'Crème brûlée à la vanille de Madagascar', 'Une crème onctueuse parfumée à la vanille, recouverte d’une fine couche de sucre caramélisé à la flamme.', 7.5, 'img/dessert_creme_brulee_a_la_vanille_de_madagascar.jpg'),
(52, 'dessert', 'Moelleux au chocolat cœur fondant', 'Gâteau chaud au chocolat noir intense, avec un cœur coulant et une boule de glace vanille en accompagnement.', 8.9, 'img/dessert_moelleux_au_chocolat_coeur_fondant.jpg'),
(53, 'dessert', 'Tartelette aux fruits rouges', 'Pâte sablée croustillante garnie d’une crème pâtissière légère et de fruits rouges frais.', 6.8, 'img/dessert_tartelette_aux_fruits_rouges.jpg'),
(54, 'dessert', 'Tarte citron meringuée', 'Crème de citron acidulée sur fond de pâte sucrée, recouverte d’une meringue dorée au chalumeau.', 7.2, 'img/dessert_tarte_citron_meringuee.jpg'),
(55, 'dessert', 'Cheesecake au coulis de fruits rouges', 'Cheesecake crémeux à base de fromage frais, sur biscuit croquant, nappé d’un coulis de framboise.', 7.9, 'img/dessert_cheesecake_au_coulis_de_fruits_rouges.jpg'),
(56, 'dessert', 'Tiramisu traditionnel italien', 'Biscuit imbibé de café et mascarpone onctueux, saupoudré de cacao pur.', 8.5, 'img/dessert_tiramisu_traditionnel_italien.jpg'),
(57, 'dessert', 'Crumble aux pommes et cannelle', 'Pommes fondantes parfumées à la cannelle, recouvertes d’un crumble doré et croustillant.', 6.5, 'img/dessert_crumble_aux_pommes_et_cannelles.jpg'),
(58, 'dessert', 'Fondant coco et coulis de mangue', 'Petit gâteau moelleux à la noix de coco, accompagné d’un coulis de mangue exotique.', 7, 'img/dessert_fondant_coco_et_coulis_de_mangue.jpg'),
(59, 'dessert', 'Coupe glacée “Chocolat Intense”', 'Trois boules de glace au chocolat, chantilly maison, éclats de brownie et sauce chocolat chaude.', 9.2, 'img/dessert_coupe_glacee_chocolat_intense.jpg'),
(60, 'dessert', 'Panna cotta au miel et éclats de pistache', 'Crème italienne douce et légère, parfumée au miel de fleurs et agrémentée de pistaches croquantes.', 7.4, 'img/dessert_panna_cotta_au_miel_et_eclats_de_pistache.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `iduser` int(25) NOT NULL COMMENT 'id de l''utilisateur',
  `login_utilisateur` varchar(25) DEFAULT NULL COMMENT 'login de l''utilisateur',
  `email_utilisateur` varchar(255) DEFAULT NULL COMMENT 'email de l''utilisateur',
  `mot_de_passe_utilisateur` text DEFAULT NULL COMMENT 'mot de passe de l''utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`iduser`, `login_utilisateur`, `email_utilisateur`, `mot_de_passe_utilisateur`) VALUES
(1, 'lasv_lya', 'LLaa@gmail.com', '$2y$10$mTUKPPADJGEdriuzFhTg5.4iYNT3fEGNclb122l6M3DfwzPiYToCm'),
(2, 'TONON_Raphael', 'raphael.tonon@gmail.com', '$2y$10$4AHoF2zB.yi3fibORVABNuoUl/ZlHr0w7vp6BP4oh4.ZYWgGxGnFW');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idcommande`),
  ADD KEY `FK_COMETAT` (`idetat`),
  ADD KEY `FK_COMUSER` (`iduser`);

--
-- Index pour la table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`idetat`);

--
-- Index pour la table `ligne_de_commande`
--
ALTER TABLE `ligne_de_commande`
  ADD KEY `idcommande` (`idcommande`),
  ADD KEY `idproduit` (`idproduit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`idproduit`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `idcommande` int(25) NOT NULL AUTO_INCREMENT COMMENT 'id de la commande', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `etat`
--
ALTER TABLE `etat`
  MODIFY `idetat` int(25) NOT NULL AUTO_INCREMENT COMMENT 'id de l''état de la commande', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `idproduit` int(25) NOT NULL AUTO_INCREMENT COMMENT 'id du produit', AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `iduser` int(25) NOT NULL AUTO_INCREMENT COMMENT 'id de l''utilisateur', AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_COMETAT` FOREIGN KEY (`idetat`) REFERENCES `etat` (`idetat`),
  ADD CONSTRAINT `FK_COMUSER` FOREIGN KEY (`iduser`) REFERENCES `utilisateur` (`iduser`);

--
-- Contraintes pour la table `ligne_de_commande`
--
ALTER TABLE `ligne_de_commande`
  ADD CONSTRAINT `ligne_de_commande_ibfk_1` FOREIGN KEY (`idcommande`) REFERENCES `commande` (`idcommande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ligne_de_commande_ibfk_2` FOREIGN KEY (`idproduit`) REFERENCES `produit` (`idproduit`) ON DELETE CASCADE ON UPDATE CASCADE;

-- ========================================================
-- Triggers
-- ========================================================

-- 3.1. before_ligne_insert
-- A chaque ajout d'une ligne de commande, calcule le "total ligne HT"
DROP TRIGGER IF EXISTS `before_ligne_insert`;
DELIMITER $$
CREATE TRIGGER `before_ligne_insert`
BEFORE INSERT ON `ligne_de_commande`
FOR EACH ROW
BEGIN
  DECLARE v_prix_ht DOUBLE DEFAULT 0;
  SELECT `prixproduit` INTO v_prix_ht FROM `produit`
  WHERE `idproduit` = NEW.`idproduit`;
  SET NEW.`total_ht` = v_prix_ht * NEW.`quantite`;
END$$
DELIMITER ;

-- 3.2. before_ligne_update
-- A chaque modification d'une ligne de commande, met à jour le "total ligne HT"
DROP TRIGGER IF EXISTS `before_ligne_update`;
DELIMITER $$
CREATE TRIGGER `before_ligne_update`
BEFORE UPDATE ON `ligne_de_commande`
FOR EACH ROW
BEGIN
  DECLARE v_prix_ht DOUBLE DEFAULT 0;
  SELECT `prixproduit` INTO v_prix_ht FROM `produit`
  WHERE `idproduit` = NEW.`idproduit`;
  SET NEW.`total_ht` = v_prix_ht * NEW.`quantite`;
END$$
DELIMITER ;

-- 3.3. after_ligne_insert
-- A chaque ajout d'une ligne de commande, recalcule le montant TTC de la commande
DROP TRIGGER IF EXISTS `after_ligne_insert`;
DELIMITER $$
CREATE TRIGGER `after_ligne_insert`
AFTER INSERT ON `ligne_de_commande`
FOR EACH ROW
BEGIN
  DECLARE v_total_prix_ht DOUBLE DEFAULT 0;
  DECLARE v_type_commande INT DEFAULT 1;

  SELECT SUM(`total_ht`) INTO v_total_prix_ht
  FROM `ligne_de_commande`
  WHERE `idcommande` = NEW.`idcommande`;

  SELECT `type_commande` INTO v_type_commande
  FROM `commande`
  WHERE `idcommande` = NEW.`idcommande`
  LIMIT 1;

  IF v_type_commande = 1 THEN
    UPDATE `commande`
    SET `montant_ttc` = v_total_prix_ht * (1 + (5.5/100))
    WHERE `idcommande` = NEW.`idcommande`;
  ELSE
    UPDATE `commande`
    SET `montant_ttc` = v_total_prix_ht * (1 + (10/100))
    WHERE `idcommande` = NEW.`idcommande`;
  END IF;
END$$
DELIMITER ;

-- 3.4. after_ligne_update
-- A chaque modification d'une ligne de commande, recalcule le montant TTC de la commande
DROP TRIGGER IF EXISTS `after_ligne_update`;
DELIMITER $$
CREATE TRIGGER `after_ligne_update`
AFTER UPDATE ON `ligne_de_commande`
FOR EACH ROW
BEGIN
  DECLARE v_total_prix_ht DOUBLE DEFAULT 0;
  DECLARE v_type_commande INT DEFAULT 1;

  SELECT SUM(`total_ht`) INTO v_total_prix_ht
  FROM `ligne_de_commande`
  WHERE `idcommande` = NEW.`idcommande`;

  SELECT `type_commande` INTO v_type_commande
  FROM `commande`
  WHERE `idcommande` = NEW.`idcommande`
  LIMIT 1;

  IF v_type_commande = 1 THEN
    UPDATE `commande`
    SET `montant_ttc` = v_total_prix_ht * (1 + (5.5/100))
    WHERE `idcommande` = NEW.`idcommande`;
  ELSE
    UPDATE `commande`
    SET `montant_ttc` = v_total_prix_ht * (1 + (10/100))
    WHERE `idcommande` = NEW.`idcommande`;
  END IF;
END$$
DELIMITER ;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
