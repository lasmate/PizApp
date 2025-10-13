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
  `nomproduit` varchar(255) NOT NULL,
  `libproduit` text DEFAULT NULL COMMENT 'description du produit',
  `prixproduit` double DEFAULT NULL COMMENT 'prix du produit',
  `imgproduit` varchar(255) DEFAULT NULL COMMENT 'Lien de l''image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`idproduit`, `nomproduit`, `libproduit`, `prixproduit`, `imgproduit`) VALUES
(1, 'Pizza Margherita', 'Tomates, mozzarella, basilic', 12.5, 'img/'),
(2, 'Pizza Pepperoni', 'Pepperoni, mozzarella, sauce tomate', 14.9, 'img/'),
(3, 'Pizza 4 Fromages', 'Mozzarella, gorgonzola, parmesan, chèvre', 16.5, 'img/'),
(4, 'Coca Cola', 'Boisson gazeuse 33cl', 2.5, 'img/'),
(5, 'Tiramisu', 'Dessert italien traditionnel', 6.9, 'img/'),
(6, 'Salade César', 'Salade, poulet, croûtons, parmesan', 11.5, 'img/'),
(7, 'Eau Minérale', 'Eau plate 50cl', 1.8, 'img/'),
(8, 'Panna Cotta', 'Dessert italien aux fruits rouges', 5.9, 'img/');

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
