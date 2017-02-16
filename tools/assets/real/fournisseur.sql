-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Jeu 16 Février 2017 à 06:41
-- Version du serveur :  5.7.16
-- Version de PHP :  7.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `zilu`
--

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `email` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id`, `nom`, `email`) VALUES
(74, 'WOHLSTAND', ''),
(75, 'RISING', ''),
(76, 'BODY TONE', ''),
(77, 'MUSIC STOCK', ''),
(78, 'TLM', ''),
(79, 'AZUNI', ''),
(80, 'AMAYA', ''),
(81, 'WATERFLEX', ''),
(82, 'ELINA', ''),
(83, 'RECORD', ''),
(84, 'TOP ASIA', ''),
(85, 'AXESS', ''),
(86, 'REEBOK', ''),
(87, 'GYMWAY', ''),
(88, 'KYLIN', ''),
(89, 'HELISPORTS', ''),
(90, 'METAL BOXE', ''),
(91, 'SKYLINE', ''),
(92, 'BOSU', ''),
(93, 'KING AND KING', ''),
(94, 'MODERNSPORTING', ''),
(95, 'AKKUA', ''),
(96, 'TOTAL GYM', ''),
(97, 'PLANET CAOUTCHOUC', ''),
(98, 'CSP', ''),
(99, 'JTB STORE', ''),
(100, 'LIONFITNESS', ''),
(101, 'TV TOURS', ''),
(102, 'UFINE', ''),
(103, 'SVELTUS', ''),
(104, 'GIBBON', ''),
(105, 'FLE-XX', ''),
(106, 'OMNI GYM', ''),
(107, 'KLIFESPORT', ''),
(108, 'HERRMAN', ''),
(109, 'IRONMAN', ''),
(110, 'TENREV', ''),
(111, 'CAMEO', ''),
(112, 'EVA FOAM', ''),
(113, 'CONCEPT 2', ''),
(114, 'XIAMEN', ''),
(115, 'BODYTONE', ''),
(116, 'VORTEC', ''),
(117, 'AMPHORA', ''),
(118, 'DE GASQUET', ''),
(119, 'VIGOT', ''),
(120, 'ADVERBUM', ''),
(121, 'K-WELL', ''),
(122, 'CHIRON', ''),
(123, 'KABBANI', ''),
(124, 'TOPASIA', ''),
(125, 'HANGZOU', '');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
