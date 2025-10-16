-- Active: 1759755836315@@127.0.0.1@3306@db_pizapp
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 12:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pizapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `idcommande` int(25) NOT NULL COMMENT 'id de la commande',
  `date_heure_commande` datetime(6) DEFAULT NULL COMMENT 'date et heure de la commande',
  `montant_ttc` double DEFAULT NULL COMMENT 'montant tout taxe comprise de la commande',
  `type_commande` tinyint(1) DEFAULT NULL COMMENT 'le type de la commande',
  `iduser` int(11) NOT NULL COMMENT 'id de l''utilisateur',
  `idetat` int(25) NOT NULL COMMENT 'id de l''état'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `etat`
--

CREATE TABLE `etat` (
  `idetet` int(25) NOT NULL COMMENT 'id de l''état de la commande',
  `libetat` text NOT NULL COMMENT 'description de l''état de la commande'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `etat`
--

INSERT INTO `etat` (`idetet`, `libetat`) VALUES
(1, 'En préparation'),
(2, 'Prête'),
(3, 'Livrée'),
(4, 'Annulée');

-- --------------------------------------------------------

--
-- Table structure for table `ligne_de_commande`
--

CREATE TABLE `ligne_de_commande` (
  `idcommande` int(25) NOT NULL COMMENT 'id de la commande',
  `idproduit` int(25) NOT NULL COMMENT 'id du produit commandé',
  `quantite` int(255) DEFAULT NULL COMMENT 'quantité du produit commandé',
  `total_ht` int(255) DEFAULT NULL COMMENT 'total des produit HT '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `idproduit` int(11) NOT NULL COMMENT 'id du produit',
  `typeproduit` enum('pizza','boisson','dessert','autre') DEFAULT 'autre' COMMENT 'type du produit',
  `nomproduit` varchar(255) NOT NULL,
  `libproduit` text DEFAULT NULL COMMENT 'description du produit',
  `prixproduit` double DEFAULT NULL COMMENT 'prix du produit',
  `imgproduit` varchar(255) DEFAULT NULL COMMENT 'Lien de l''image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO produit (idproduit, typeproduit, nomproduit, libproduit, prixproduit, imgproduit) VALUES
(NULL, 'Pizza', 'MARGHERITA', 'tomate_fromage_olives_origan', 9.50, 'img/'),
(NULL, 'Pizza', 'ROMAINE', 'tomate_jambon_fromage_olives_origan', 11.00, 'img/'),
(NULL, 'Pizza', 'REINE', 'tomate_jambon_champignons_fromage_olives_origan', 11.50, 'img/'),
(NULL, 'Pizza', 'FORESTIERE', 'tomate_champignons_persillade_crème_fromage_olives_origan', 11.50, 'img/'),
(NULL, 'Pizza', 'CHORIZO', 'tomate_chorizo_poivrons_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'ORIENTALE', 'tomate_merguez_poivrons_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', '4 FROMAGES', 'tomate_chèvre_Roquefort_fromages_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'SEGUIN', 'tomate_jambon_chèvre_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'CALZONE', 'tomate_jambon_champignons_oeuf_fromage_olives_origan', 13.00, 'img/'),
(NULL, 'Pizza', 'BOLOGNAISE', 'tomate_viande-hachée_champignons_crème_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'MEXICAINE', 'tomate_viande-hachée-épicée_oignons_poivrons_maïs_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'POULET', 'tomate_poulet_champignons_crème_fromages_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'ANCHOIS', 'tomate_anchois_câpres_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'VEGETARIENNE', 'tomate_champignons_oignons_artichauts_poivrons_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'ROQUEFORT', 'tomate_Roquefort_jambon_fromage_olives_origan', 12.00, 'img/'),
(NULL, 'Pizza', 'FRUITS DE MER', 'tomate_fruits_de_mer_persillade_fromage_olives_origan', 13.50, 'img/'),
(NULL, 'Pizza', 'CAMPAGNARDE', 'tomate_lardons_oignons_oeuf_crème_fromage_olives_origan', 13.00, 'img/'),
(NULL, 'Pizza', 'PECHEUR', 'tomate_thon_persillade_crème_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'HAWAIENNE', 'crème_jambon_ananas_curry_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'FLAMICHE', 'crème_lardons_oignons_fromage_olives_origan', 12.50, 'img/'),
(NULL, 'Pizza', 'TARTIFLETTE', 'crème_lardons_pomme_de_terre_reblochon_fromage_olives', 13.50, 'img/'),
(NULL, 'Pizza', 'MONTAGNARDE', 'crème_diot_de_Savoie_oignons_tomme_de_Savoie_pomme_de_terre_fromage_olives_origan', 13.50, 'img/'),
(NULL, 'Pizza', 'RAVIOLE', 'crème_ravioles_fromage_olives_origan', 13.50, 'img/'),
(NULL, 'Pizza', 'RAVIOLE SAUMON', 'crème_ravioles_saumon_fromage_olives_origan', 14.00, 'img/'),
(NULL, 'Pizza', 'MURETINE', 'crème_st_Marcellin_persillade_fromage_olives_origan', 13.00, 'img/'),
(NULL, 'Pizza', 'RACLETTE', 'crème_raclette_jambon_cru_pomme_de_terre_fromage_olives_origan', 13.50, 'img/'),
(NULL, 'Pizza', 'SAUMON', 'crème_persillade_saumon_fromage_olives_origan', 13.50, 'img/'),
(NULL, 'Pizza', 'GRENOBLOISE', 'crème_chèvre_miel_noix_fromage_olives_origan', 13.00, 'img/'),
(NULL, 'Pizza', 'KEBAB', 'crème_viande_kebab_sauce_kebab_tomate_fraîche_fromage_olives_origan', 13.00, 'img/'),
(NULL, 'Pizza', 'PIZZA SUCREE', 'crème_Nutella_spéculoos_ou_noix_de_coco', 9.50, 'img/');


INSERT INTO produit (idproduit, typeproduit, nomproduit, libproduit, prixproduit, imgproduit) VALUES
(NULL, 'Boisson', 'Pepsi', "Boisson iconique au goût frais et intense, c'est une boisson gazeuse délicieuse et rafraîchissante aux extraits végétaux", 2.0, 'img/'),
(NULL, 'Boisson', 'Pepsi MAX', 'La version sans sucre du Pepsi, tout le plaisir sans les calories, toujours aussi rafraîchissant.', 2.0,'img/'),
(NULL, 'Boisson', 'KAS', 'Boisson pétillante aux saveurs fruitées, idéale pour une pause fraîcheur.', 1.8, 'img/'),
(NULL, 'Boisson', 'Lipton', 'Thé glacé désaltérant, parfait pour se rafraîchir à tout moment de la journée.', 2.0, 'img/'),
(NULL, 'Boisson', 'Oasis', 'Boisson aux fruits sans bulles, douce et fruitée, pour petits et grands.', 2.0, 'img/'),
(NULL, 'Boisson', 'Coca-cola', 'La boisson gazeuse la plus célèbre au monde, goût unique et rafraîchissant.', 2.0, 'img/'),
(NULL, 'Boisson', 'Fanta', 'Boisson pétillante à l’orange, fun et fruitée, pour une explosion de saveurs.', 1.8, 'img/'),
(NULL, 'Boisson', 'Sprite', 'Boisson gazeuse citron-lime, ultra rafraîchissante et désaltérante.', 1.8, 'img/'),
(NULL, 'Boisson', 'Nestea', 'Thé glacé au goût subtil, parfait pour une pause fraîcheur.', 2.0, 'img/'),
(NULL, 'Boisson', 'Minute Maid', 'Jus de fruits savoureux, 100% plaisir, 100% fruits.', 2.0, 'img/'),
(NULL, 'Boisson', 'Orangina', 'Boisson pétillante à l’orange, pulpeuse et pleine de peps.', 2.0, 'img/'),
(NULL, 'Boisson', 'Schweppes', 'Boisson gazeuse au goût amer unique, idéale pour l’apéritif.', 2.0, 'img/'),
(NULL, 'Boisson', 'Ricqles', 'Menthe forte et rafraîchissante, pour un coup de frais instantané.', 2.2, 'img/'),
(NULL, 'Boisson', 'Perrier', 'Eau minérale gazeuse naturelle, bulles fines et rafraîchissantes.', 1.5, 'img/'),
(NULL, 'Boisson', 'Pschitt', 'Boisson pétillante au citron ou à l’orange, rétro et désaltérante.', 1.8, 'img/'),
(NULL, 'Boisson', 'Cacolac', 'Boisson lactée au cacao, douceur et gourmandise à l’état pur.', 2.0, 'img/'),
(NULL, 'Boisson', 'Granini', 'Jus de fruits premium, saveur intense et naturelle.', 2.2, 'img/'),
(NULL, 'Boisson', 'Joker', 'Jus de fruits variés, douceur et vitamines pour toute la famille.', 2.0, 'img/'),
(NULL, 'Boisson', 'Red Bull', 'Boisson énergisante, donne des ailes pour affronter la journée.', 2.5, 'img/'),
(NULL, 'Boisson', 'Monster', 'Boisson énergisante au goût puissant, pour un maximum d’énergie.', 2.5, 'img/');


INSERT INTO produit (idproduit, typeproduit, nomproduit, libproduit, prixproduit, imgproduit) VALUES
(NULL, 'Dessert', 'Crème brûlée à la vanille de Madagascar', 'Une crème onctueuse parfumée à la vanille, recouverte d’une fine couche de sucre caramélisé à la flamme.', 7.50, 'img/'),
(NULL, 'Dessert', 'Moelleux au chocolat cœur fondant', 'Gâteau chaud au chocolat noir intense, avec un cœur coulant et une boule de glace vanille en accompagnement.', 8.90, 'img/'),
(NULL, 'Dessert', 'Tartelette aux fruits rouges', 'Pâte sablée croustillante garnie d’une crème pâtissière légère et de fruits rouges frais.', 6.80, 'img/'),
(NULL, 'Dessert', 'Tarte citron meringuée', 'Crème de citron acidulée sur fond de pâte sucrée, recouverte d’une meringue dorée au chalumeau.', 7.20, 'img/'),
(NULL, 'Dessert', 'Cheesecake au coulis de fruits rouges', 'Cheesecake crémeux à base de fromage frais, sur biscuit croquant, nappé d’un coulis de framboise.', 7.90, 'img/'),
(NULL, 'Dessert', 'Tiramisu traditionnel italien', 'Biscuit imbibé de café et mascarpone onctueux, saupoudré de cacao pur.', 8.50, 'img/'),
(NULL, 'Dessert', 'Crumble aux pommes et cannelle', 'Pommes fondantes parfumées à la cannelle, recouvertes d’un crumble doré et croustillant.', 6.50, 'img/'),
(NULL, 'Dessert', 'Fondant coco et coulis de mangue', 'Petit gâteau moelleux à la noix de coco, accompagné d’un coulis de mangue exotique.', 7.00, 'img/'),
(NULL, 'Dessert', 'Coupe glacée “Chocolat Intense”', 'Trois boules de glace au chocolat, chantilly maison, éclats de brownie et sauce chocolat chaude.', 9.20, 'img/'),
(NULL, 'Dessert', 'Panna cotta au miel et éclats de pistache', 'Crème italienne douce et légère, parfumée au miel de fleurs et agrémentée de pistaches croquantes.', 7.40, 'img/');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `iduser` int(25) NOT NULL COMMENT 'id de l''utilisateur',
  `login_utilisateur` varchar(25) DEFAULT NULL COMMENT 'login de l''utilisateur',
  `email_utilisateur` varchar(255) DEFAULT NULL COMMENT 'email de l''utilisateur',
  `mot_de_passe_utilisateur` text DEFAULT NULL COMMENT 'mot de passe de l''utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`iduser`, `login_utilisateur`, `email_utilisateur`, `mot_de_passe_utilisateur`) VALUES
(1, 'lasv_lya', 'LLaa@gmail.com', '$2y$10$mTUKPPADJGEdriuzFhTg5.4iYNT3fEGNclb122l6M3DfwzPiYToCm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`idcommande`),
  ADD UNIQUE KEY `iduser` (`iduser`),
  ADD UNIQUE KEY `idetat` (`idetat`);

--
-- Indexes for table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`idetet`);

--
-- Indexes for table `ligne_de_commande`
--
ALTER TABLE `ligne_de_commande`
  ADD PRIMARY KEY (`idcommande`,`idproduit`),
  ADD KEY `idproduit` (`idproduit`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`idproduit`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `idcommande` int(25) NOT NULL AUTO_INCREMENT COMMENT 'id de la commande';

--
-- AUTO_INCREMENT for table `etat`
--
ALTER TABLE `etat`
  MODIFY `idetet` int(25) NOT NULL AUTO_INCREMENT COMMENT 'id de l''état de la commande', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `idproduit` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id du produit', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `iduser` int(25) NOT NULL AUTO_INCREMENT COMMENT 'id de l''utilisateur', AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`idcommande`) REFERENCES `ligne_de_commande` (`idcommande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`iduser`) REFERENCES `utilisateur` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commande_ibfk_3` FOREIGN KEY (`idetat`) REFERENCES `etat` (`idetet`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ligne_de_commande`
--
ALTER TABLE `ligne_de_commande`
  ADD CONSTRAINT `ligne_de_commande_ibfk_1` FOREIGN KEY (`idproduit`) REFERENCES `produit` (`idproduit`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
