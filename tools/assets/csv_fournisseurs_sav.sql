-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 15 Février 2017 à 13:06
-- Version du serveur :  5.6.25
-- Version de PHP :  5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `zilu`
--

-- --------------------------------------------------------

--
-- Structure de la table `csv_fournisseurs_sav`
--

CREATE TABLE IF NOT EXISTS `csv_fournisseurs_sav` (
`id` int(11) NOT NULL,
  `fournisseur` varchar(45) NOT NULL,
  `reference_lf` varchar(45) NOT NULL,
  `produit` varchar(128) NOT NULL,
  `livre_le` varchar(45) NOT NULL,
  `quantite` varchar(45) NOT NULL,
  `prix` varchar(45) NOT NULL,
  `nb_produits_defec` varchar(45) NOT NULL,
  `date_notif` varchar(45) NOT NULL,
  `demande_remboursement` varchar(45) NOT NULL,
  `montant_rembourse` varchar(45) NOT NULL,
  `remboursement` varchar(128) NOT NULL,
  `forme` varchar(128) NOT NULL,
  `statut` varchar(45) NOT NULL,
  `avoir_lf` varchar(45) NOT NULL,
  `date_remboursement` text NOT NULL,
  `problemes` text NOT NULL,
  `avancement` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Contenu de la table `csv_fournisseurs_sav`
--

INSERT INTO `csv_fournisseurs_sav` (`id`, `fournisseur`, `reference_lf`, `produit`, `livre_le`, `quantite`, `prix`, `nb_produits_defec`, `date_notif`, `demande_remboursement`, `montant_rembourse`, `remboursement`, `forme`, `statut`, `avoir_lf`, `date_remboursement`, `problemes`, `avancement`) VALUES
(2, 'WOHLSTAND', '3631', 'Bosus Roses', '24/09/14', '80', '29.40', '80', '03/11/14', '2', '2', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC0911', 'Ils explosent', ''),
(3, 'WOHLSTAND', '1559', 'Foam roller blanc', '10/09/14', '1000', '5.65', '500', '19/10/14', '2', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', '500 renvoy?s gratuitement', 'Ils ne sont pas cylindrique, pas de la m?me taille', ''),
(4, 'WOHLSTAND', '1682', 'Pilates ring', '15/02/14', '3000', '2.75', '500', '19/10/14', '1', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', '500 renvoy?s gratuitement', 'Ils ne tiennent pas, la mousse se d?colle', ''),
(5, 'WOHLSTAND', '1682', 'Pilates ring', '10/09/14', '500', '0.00', '500', '19/10/14', '1', '1', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC0777', 'Ils ne tiennent pas, la mousse se d?colle', ''),
(6, 'WOHLSTAND', '1697', 'Rack 10 Kit Pump', '15/02/14', '60', '41.00', '20', '19/10/14', '820.00', '410.00', '50%', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC0911', 'Pr?sence de rouille sur les produits', ''),
(7, 'WOHLSTAND', '1716', 'Rack Mural', '24/09/14', '100', '4.80', '100', '19/10/14', '480.00', '480.00', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC0777', 'Espace trop court entre les barres , les tapis ne rentrent pas', ''),
(8, 'WOHLSTAND', '1445 / 1447', 'Ballon Paille Bleu/violet', '15/02/14', '2800', '0.44', '1400', '19/10/14', '616.00', '616.00', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC0777', 'Produits ovals et de diff?rentes tailles', ''),
(9, 'WOHLSTAND', '2345', 'Rack 5 medecine balls', '20/02/15', '5', '24.40', '5', '25/03/15', '122.00', '122.00', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1153', 'il y a du jeu entre les 2 parties du rack', ''),
(10, 'WOHLSTAND', '2347', 'Rack Power bag', '10/09/14', '5', '57.40', '5', '02/04/15', '143.50', '143.50', '50%', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1153', 'il y a du jeu entre les 2 parties du rack', ''),
(11, 'WOHLSTAND', '', 'Halt?res hexa caoutchouc', '10/09/14', '8', '0.00', '8', '02/04/15', '259.22', '259.22', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1153', 'Les halt?res se dessoudent, -2 x 20kgs  (2 x 22,54$ = 45,08$)\r-1 x 27,5kgs (1 x 31$ = 31$)\r-3 x 30kgs (3 x 33,81$ = 101,43$)\r-1 x 32,5kgs (1 x 36,63$ = 36,63$)\r-1 x 40kgs (1 x 45,08$ = 45,08$)', ''),
(12, 'WOHLSTAND', '1453', 'Barres olympiques', '24/09/14', '100', '34.40', '1', '06/07/15', '34.40', '34.40', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1153', 'La barre a c?d?', ''),
(13, 'WOHLSTAND', '1699', 'Rack 15 kit pump', '27/10/14 et 15/02/2015', '130', '81.20', '130', '21/09/15', '2', '2', '20%', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1331', 'les racks ne peuvent contenir que 15 kit pump au lieu de 20.', ''),
(14, 'WOHLSTAND', '', 'Rack halt?res hex caoutchouc + halt?res', '', '10 paires de 1 ? 20kgs + racks', '376.60', 'Tous', '21/09/15', '376.60', '376.60', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1331', 'caoutchouc se d?colle et a abim? les halt?res', ''),
(15, 'WOHLSTAND', '2239', 'rack halt?res vinyles', '', '8', '76.80', '8', '08/10/15', '614.40', '614.40', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1405', 'espacement pas bons', ''),
(16, 'WOHLSTAND', '2239', 'rack halt?res vinyles', '', '15', '76.80', '15', '08/10/15', '288.00', '0.00', '25%', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1405', 'Pas de barre de soutien. Pas de trous dans la barre de fermeture', ''),
(17, 'WOHLSTAND', '4008', 'anneaux gym bois', '03/09/15', '60', '11.68', '2', '28/10/15', '23.36', '23.36', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde WSC1405', 'le clip de fermeture se casse', ''),
(18, 'WOHLSTAND', '1510', 'disques noirs 15kgs', '01/02/15', '10', '16.43', '1', '27/11/15', '16.43', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Envoi avec cde WSC1383', 'la bague se d?tache', ''),
(19, 'WOHLSTAND', '2485', 'Halt?res hexa 42,5kgs', '01/10/14', '2', '47.90', '1', '16/12/15', '47.90', '47.90', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur Cde WSC1422', 'L''halt?re se d?tache', ''),
(20, 'WOHLSTAND', '1591', 'Halt?res hexa 17,5kgs', '15/02/15', '4', '19.95', '1', '27/01/16', '19.95', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Renvoi de pdts', 'L''halt?re se d?tache', ''),
(21, 'WOHLSTAND', '2353', 'Fitness Tube Medium', '15/10/15', '500', '1.96', '2', '27/01/16', '4.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Renvoi de pdts', 'L''halt?re se d?tache', ''),
(22, 'WOHLSTAND', '2315', 'Disque Pump 5Kgs - Orange', '01/03/16', '600', '5.45', '600', '15/03/16', '3', '0.00', '', '', 'En attente', '', '', 'La finition n''est pas bonne du tout. L''int?rieur du trou est frott?.', ''),
(23, 'WOHLSTAND', '2313', 'Disque Pump 1,25Kg - Rose', '01/03/16', '100', '1.40', '100', '15/03/16', '140.00', '0.00', '', '', 'En attente', '', '', 'La finition n''est pas bonne du tout. L''int?rieur du trou est frott?.', ''),
(24, 'WOHLSTAND', '2314', 'Disque Pump 2,5Kgs - Jaune', '01/03/16', '200', '2.80', '200', '15/03/16', '560.00', '0.00', '', '', 'En attente', '', '', 'La finition n''est pas bonne du tout. L''int?rieur du trou est frott?.', ''),
(25, 'WOHLSTAND', '2315', 'Disque Pump 5Kgs - Vert', '01/03/16', '200', '5.60', '200', '15/03/16', '1', '0.00', '', '', 'En attente', '', '', 'La finition n''est pas bonne du tout. L''int?rieur du trou est frott?.', ''),
(26, 'WOHLSTAND', '2320', 'Disque Pump 10Kg - Noir', '01/03/16', '200', '10.20', '200', '15/03/16', '2', '0.00', '', '', 'En attente', '', '', 'La finition n''est pas bonne du tout. L''int?rieur du trou est frott?.', ''),
(27, 'Wohlstand', '2687', 'Steps sans plots', '01/03/16', '90', '9.84', '10', '29/03/16', '98.40', '0.00', '', '', 'En attente', '', '', 'Il y a des enfoncements', ''),
(28, 'WOHLSTAND', '4008', 'anneaux gym bois', '01/03/16', '10', '11.68', '1', '31/03/16', '11.68', '0.00', 'Complet', 'Renvoi de pdts', 'ok', '', 'Renvoi container B9', 'le clip de fermeture se casse', ''),
(29, 'WOHLSTAND', '1453', 'Barre olympique', '', '20', '37.48', '1', '13/05/16', '37.48', '0.00', '', '', 'En attente', '', '', '', ''),
(30, 'WOHLSTAND', '1717', 'Rack ? disques', '20/02/16', '40', '32.00', '1', '31/05/16', '32.00', '0.00', 'Complet', 'Remboursement', 'ok', '', 'Pr?lev? sur cde WSC1578', 'la rack se tord sous le poid des disques', ''),
(31, 'RISING', '3479', 'Cordes d''oscillation', '16/05/15', '15', '42.00', '15', '16/07/15', '630.00', '630.00', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde RSL15240A', 'Les cordes s''effritent', ''),
(32, 'RISING', '1944', 'Trampolines', '02/09/15', '500', '17.00', '190', '15/09/15', '850.00', '255.00', '3%', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde RSL15760', 'Pr?sence de  boule de soudure sur les pattes', ''),
(33, 'RISING', '3550', 'Monkey rack', '23/09/14', '5', '107.00', '1', '20/10/15', '107.00', '32.00', '29%', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde RSL15689', 'Les monkey bars se tordent. normal finalement. Remboursent juste erreur de prix', ''),
(34, 'RISING', '3479', 'Corde d''oscillation', '15/10/15', '20', '54.00', '1', '21/10/15', '54.00', '54.00', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde RSL15760', 'les poign?es se cassent', ''),
(35, 'RISING', '3694 et 3695', 'Power Bags', '24/03/14', '2', '51.50', '2', '27/10/15', '51.50', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Renvoi de produits', 'power bags se d?t?riore rapidement', ''),
(36, 'RISING', '3479', 'Cordes d''oscillations', '17/09/15', '15', '42.00', '15', '16/07/15', '630.00', '630.00', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde RSL15760', 'Les poign?es de cordes s''effritent', ''),
(37, 'RISING', '3562', 'Trampoline cage', '16/05/15', '5', '80.00', '1', '05/01/15', '80.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Renvoi de pdts', '', ''),
(38, 'RISING', '', 'Slamball 4kg', '17/09/15', '20', '4.00', '1', '17/12/15', '4.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Renvoi de pdts', '', ''),
(39, 'RISING', '', 'Slamball 4kg', '17/09/15', '20', '4.00', '1', '22/01/16', '4.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Renvoi de pdts', '', ''),
(40, 'RISING', '', 'Sangle Suspension trainer', '01/03/16', '200', '17.00', '2', '07/03/16', '0.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', '', 'dans container A9', 'Le mousqueton a lach?, on redemande la sangle compl?te', ''),
(41, 'RISING', '1445', 'Ballon paille bleu', '01/03/16', '1600', '0.54', '1600', '11/03/16', '864.00', '0.00', '', 'Remboursement', 'En attente', '', '', 'Les tailles sont diff?rentes pour chaque ballons', ''),
(42, 'RISING', '1944', 'Trampolines', '02/09/15', '500', '17.00', '30', '15/09/15', '0.00', '0.00', '', 'Renvoi de 30 pieds', 'En attente', '', '', 'Les filetages ne sont pas bons, et l''acier n''est pas assez ?pais. On demande le renvoi de 30 pieds + le renvoi de 15 trampolines', 'Je leur renvoi plus de photos. Relance le 19/07. Vont nous renvoyer 30 pieds dans un container. J''essaye de n?gocier les 15 autres trampolins'),
(43, 'RISING', '3689', 'Triangular Crosses', '23/09/14', '20', '86.50', '1', '30/03/16', '86.50', '0.00', 'Complet', 'Renvoi de produit', 'ok', '', '', 'la barre est abim?e', ''),
(44, 'RISING', '', 'Sangle Suspension trainer', '01/03/16', '200', '17.00', '3', '30/03/16', '0.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', '', '', 'Le mousqueton a lach?', ''),
(45, 'RISING', '', 'Punching Bag Rack', '05/05/16', '5', '28.50', '2', '12/04/16', '0.00', '0.00', '', '', 'En attente', '', '', 'Les crochets ont lach?', ''),
(46, 'RISING', '', 'LF Suspension', '16/10/16', '200', '18.50', '200', '19/04/16', '3', '0.00', '', '', 'En attente', '', '', 'Tous les mousquetons se cassent', ''),
(47, 'RISING', '', 'Timer', '14/03/16', '20', '81.00', '1', '19/04/16', '81.00', '0.00', '', '', 'En attente', '', '', 'Le mode horloge ne fonctionne pas', ''),
(48, 'RISING', '3550', 'Monkey rack', '23/09/14', '18', '107.00', '1', '17/05/16', '107.00', '0.00', '', '', 'En attente', '', '', 'Les monkey bars se tordent', ''),
(49, 'RISING', '', 'Slamball 4kg & 6kg', '26/05/16', '20', '8.00', '2', '01/06/16', '0.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', '', 'Renvoi de pdts', '', ''),
(50, 'RISING', '', 'Plyobox noir', '16/12/15', '100', '43.90', '2', '25/07/16', '0.00', '0.00', '', 'Renvoi de pdts', '', '', '', 'La plyobox est toute endommag?e.', ''),
(51, 'TOP ASIA', '1738', 'Steps LF', '20/02/15', '296', '31.50', '5', '10/09/15', '157.50', '157.50', 'Complet', 'Remboursement', 'ok', 'ok', 'Pr?lev? sur cde 15TA5116', 'Steps cass?s ? l''ouverture du carton', ''),
(52, 'TOP ASIA', '1453', 'Barres olympiques', '20/02/15', '200', '36.97', '200', '01/09/15', '3', '3', '50%', 'Remboursement', 'ok', 'ok', '50% pr?lev? sur cde 15TA5029-2 + 50% pr?lev? sur cde 15TA5116', 'Barres de 220cm qui font 17kgs au lieu de 20kgs', ''),
(53, 'TOP ASIA', '3486', 'disques bumper 15kgs', '16/10/15', '10', '47.40', '10', '24/11/15', '474.00', '474.00', 'Complet', 'Remboursement', 'ok', 'ok', '50% Pr?lev? sur cde 16TA5006 + 50% ? pr?lever', '', ''),
(54, 'TOP ASIA', '1682', 'Pilates Ring', '01/03/16', '1000', '2.95', '1000', '16/03/16', '0.20', '0.00', '3%', 'Renvoi de produits', 'ok', 'ok', 'Renvoi de 30pcs avec 16TA006', 'Le logo n''est pr?sent que sur un c?t?', ''),
(55, 'TOP ASIA', '1430', 'Anneau Ballon', '01/03/16', '50', '4.50', '1', '08/04/16', '4.50', '0.00', 'Complet', 'Remboursement', 'ok', '', '', 'Velcro mis du m?me c?t?', ''),
(56, 'AZUNI', '1745', 'Tapis 140cm noirs', '17/02/15', '1090', '11.85', '1', '02/11/15', '11.85', '0.00', 'Complet', 'Renvoi de pdts', 'ok', 'ok', 'Tapis trop court', '', ''),
(57, 'HERMANN', '', 'Base sac de frappe', '', '', '0.00', '3', '', '0.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', '', '2 bases envoy? ? Rising', 'Les bases ?taient perc?es', ''),
(58, 'HERMANN', '', 'Zip sac de frappe + tube PVC', '', '', '0.00', '3', '', '0.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', '', 'En renvoi un de chaque ? Rising pour container A9', 'Un zip d''un couverture ?tait cass?. Il manquait un tube PVC aussi', ''),
(59, 'HERMANN', '', 'Base sac de frappe', '', '', '0.00', '2', '23/05/16', '0.00', '0.00', 'Complet', 'Renvoi de pdts', 'ok', '', 'en mets 2 dans prochain container', 'Les bases ?taient perc?es', ''),
(60, 'HERMANN', '', 'Base sac de frappe', '', '', '0.00', '1', '27/07/16', '0.00', '0.00', '', 'Renvoi de pdts', '', '', '', 'Base perc?e', ''),
(61, 'RECORD', '1748', 'Tapis 180cm', '01/03/16', '1530', '5.25', '2', '10/03/16', '10.5', '0.00', 'Complet', 'Renvoi de 4 pdts', 'ok', '', 'Envoy? avec LK16W009', 'Tapis abim?', ''),
(62, 'CSP', '3630', 'Rouleau de dalles', '08/04/16', '48', '146.25', '3', '15/09/16', '438.75', '0.00', '', '', 'En Attente', '', '', 'Les rouleaux de 10m sont coup?s en 2 (2x5m) pour certains (3-4 cas). Le fournisseur explique qu''il n''est pas possible de controler la longueur des rouleaux. Ils nous donneront une r?duction sur la prochaine commande.', ''),
(63, 'RECORD', '3421', 'Multi Grip Chin Up', '26/08/16', '20', '30.83', '20', '22/09/16', '616.6', '0.00', '', '', 'En Attente', '', '', 'Pr?sence de rouille sur la totalit? des produits', ''),
(64, 'RISING', '1938', 'SR - Upright Tube Storage Rack 1,5M', '09/09/16', '15', '20.5', '15', '07/10/16', '307.5', '0.00', '', '', 'En Attente', '', '', 'Ils sont en jaune, alors qu''on les avaient command? en noir', ''),
(65, 'RISING', '1939', 'SR - Upright Tube Storage Rack 1M', '09/09/16', '15', '15.8', '15', '07/10/16', '237', '0.00', '', '', 'En Attente', '', '', 'Ils sont en jaune, alors qu''on les avaient command? en noir', '');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `csv_fournisseurs_sav`
--
ALTER TABLE `csv_fournisseurs_sav`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `csv_fournisseurs_sav`
--
ALTER TABLE `csv_fournisseurs_sav`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
