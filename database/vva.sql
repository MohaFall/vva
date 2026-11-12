-- VVA (GACTI) : gestion des animations d'un village vacances
-- Crée la base `vva` avec des données d'exemple (comptes de démonstration : mot de passe demo1234).
-- Import : mysql -u root -p < database/vva.sql   (ou onglet Importer de phpMyAdmin)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `vva`;
CREATE DATABASE `vva` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vva`;

START TRANSACTION;

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE `activite` (
  `CODEANIM` char(8) COLLATE utf8mb4_general_ci NOT NULL,
  `DATEACT` date NOT NULL,
  `CODEETATACT` char(2) COLLATE utf8mb4_general_ci NOT NULL,
  `HRRDVACT` time DEFAULT NULL,
  `PRIXACT` decimal(7,2) DEFAULT NULL,
  `HRDEBUTACT` time DEFAULT NULL,
  `HRFINACT` time DEFAULT NULL,
  `DATEANNULEACT` date DEFAULT NULL,
  `NOMRESP` char(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PRENOMRESP` char(30) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `activite`
--

INSERT INTO `activite` (`CODEANIM`, `DATEACT`, `CODEETATACT`, `HRRDVACT`, `PRIXACT`, `HRDEBUTACT`, `HRFINACT`, `DATEANNULEACT`, `NOMRESP`, `PRENOMRESP`) VALUES
('CULT001', '2025-01-15', 'OU', '10:10:00', 10.00, '10:30:00', '11:30:00', '2025-01-16', 'Lemoine', 'Alice'),
('CULT004', '2025-01-23', 'OU', '10:00:00', 15.00, '10:30:00', '12:00:00', '2025-01-15', 'Petit', 'Julie'),
('ESCAL003', '2025-01-22', 'OU', '08:00:00', 50.00, '08:30:00', '11:30:00', NULL, 'Durand', 'Luc'),
('RAFT002', '2025-01-21', 'OU', '14:00:00', 35.00, '14:30:00', '17:00:00', NULL, 'Martin', 'Sophie'),
('RANDO001', '2025-01-20', 'OU', '09:00:00', 20.00, '09:30:00', '12:30:00', NULL, 'Dupont', 'Jean'),
('SKI005', '2025-01-16', 'OU', '14:00:00', 20.00, '14:10:00', '15:00:00', '2025-01-17', 'Petit', 'Julie'),
('SKI005', '2025-01-24', 'OU', '13:00:00', 60.00, '13:30:00', '16:30:00', '2025-01-14', 'Lemoine', 'Alice');

-- --------------------------------------------------------

--
-- Structure de la table `animation`
--

CREATE TABLE `animation` (
  `CODEANIM` char(8) COLLATE utf8mb4_general_ci NOT NULL,
  `CODETYPEANIM` char(5) COLLATE utf8mb4_general_ci NOT NULL,
  `NOMANIM` char(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DATECREATIONANIM` date DEFAULT NULL,
  `DATEVALIDITEANIM` date DEFAULT NULL,
  `DUREEANIM` double(5,0) DEFAULT NULL,
  `LIMITEAGE` int DEFAULT NULL,
  `TARIFANIM` decimal(7,2) DEFAULT NULL,
  `NBREPLACEANIM` int DEFAULT NULL,
  `DESCRIPTANIM` char(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `COMMENTANIM` char(250) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DIFFICULTEANIM` char(40) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animation`
--

INSERT INTO `animation` (`CODEANIM`, `CODETYPEANIM`, `NOMANIM`, `DATECREATIONANIM`, `DATEVALIDITEANIM`, `DUREEANIM`, `LIMITEAGE`, `TARIFANIM`, `NBREPLACEANIM`, `DESCRIPTANIM`, `COMMENTANIM`, `DIFFICULTEANIM`) VALUES
('CULT001', 'CULT', 'Blind Test', '2025-01-13', '2025-01-20', 1, 7, 15.00, 0, 'Test Musique', 'venez nombreux', 'novice'),
('CULT004', 'CULT', 'Visite Culturelle', '2025-01-01', '2025-12-31', 1, 0, 15.00, 30, 'Découverte des musées', 'Adapté à tous', 'Facile'),
('ESCAL003', 'ESCAL', 'Escalade', '2024-12-19', '2025-12-31', 2, 10, 50.00, 8, 'Cours d\'escalade en extérieur', 'Encadrement assuré', 'Difficile'),
('RAFT002', 'RAFT', 'Descente en Rafting', '2025-01-01', '2025-12-31', 2, 14, 35.00, 10, 'Rafting sur la rivière', 'Prêt du matériel inclus', 'Modéré'),
('RANDO001', 'RAND', 'Randonnée Montagne', '2025-01-01', '2025-12-31', 3, 12, 20.00, 20, 'Randonnée en montagne', 'Pour tous niveaux', 'Facile'),
('SKI005', 'SKI', 'Cours de Ski', '2025-01-01', '2025-12-31', 2, 8, 60.00, 15, 'Cours de ski alpin', 'Équipement non inclus', 'Modéré');

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `USER` char(8) COLLATE utf8mb4_general_ci NOT NULL,
  `MDP` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `NOMCOMPTE` char(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PRENOMCOMPTE` char(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DATEINSCRIP` date DEFAULT NULL,
  `DATEFERME` date DEFAULT NULL,
  `TYPEPROFIL` char(2) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DATEDEBSEJOUR` date DEFAULT NULL,
  `DATEFINSEJOUR` date DEFAULT NULL,
  `DATENAISCOMPTE` date DEFAULT NULL,
  `ADRMAILCOMPTE` char(70) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `NOTELCOMPTE` char(15) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`USER`, `MDP`, `NOMCOMPTE`, `PRENOMCOMPTE`, `DATEINSCRIP`, `DATEFERME`, `TYPEPROFIL`, `DATEDEBSEJOUR`, `DATEFINSEJOUR`, `DATENAISCOMPTE`, `ADRMAILCOMPTE`, `NOTELCOMPTE`) VALUES
('ENC001', '$2y$10$oQp.FjMyciVIWVOVMvBrJO0vksuXiAUigxGOn6k9miiEWWOTeDD2W', 'Petit', 'Julie', '2025-01-04', NULL, 'EN', NULL, NULL, '1985-09-09', 'julie.petit@email.com', '0612131415'),
('ENC002', '$2y$10$oQp.FjMyciVIWVOVMvBrJO0vksuXiAUigxGOn6k9miiEWWOTeDD2W', 'Lemoine', 'Alice', '2025-01-05', NULL, 'EN', NULL, NULL, '1992-03-19', 'alice.lemoine@email.com', '0616171819'),
('VAC001', '$2y$10$oQp.FjMyciVIWVOVMvBrJO0vksuXiAUigxGOn6k9miiEWWOTeDD2W', 'Dupont', 'Jean', '2025-01-01', NULL, 'VA', '2025-01-14', '2025-01-28', '1990-05-12', 'jean.dupont@email.com', '0601020304'),
('VAC002', '$2y$10$oQp.FjMyciVIWVOVMvBrJO0vksuXiAUigxGOn6k9miiEWWOTeDD2W', 'Martin', 'Sophie', '2025-01-02', NULL, 'VA', '2025-01-14', '2025-01-28', '1995-07-23', 'sophie.martin@email.com', '0605060708'),
('VAC003', '$2y$10$oQp.FjMyciVIWVOVMvBrJO0vksuXiAUigxGOn6k9miiEWWOTeDD2W', 'Durand', 'Luc', '2025-01-03', NULL, 'VA', '2025-01-14', '2025-01-28', '1988-11-15', 'luc.durand@email.com', '0608091011');

-- --------------------------------------------------------

--
-- Structure de la table `etat_act`
--

CREATE TABLE `etat_act` (
  `CODEETATACT` char(2) COLLATE utf8mb4_general_ci NOT NULL,
  `NOMETATACT` char(25) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etat_act`
--

INSERT INTO `etat_act` (`CODEETATACT`, `NOMETATACT`) VALUES
('AN', 'Annulé'),
('CO', 'Complet'),
('OU', 'Ouvert');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `NOINSCRIP` bigint NOT NULL,
  `USER` char(8) COLLATE utf8mb4_general_ci NOT NULL,
  `CODEANIM` char(8) COLLATE utf8mb4_general_ci NOT NULL,
  `DATEACT` date NOT NULL,
  `DATEINSCRIP` date DEFAULT NULL,
  `DATEANNULE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

INSERT INTO `inscription` (`NOINSCRIP`, `USER`, `CODEANIM`, `DATEACT`, `DATEINSCRIP`, `DATEANNULE`) VALUES
(1, 'VAC001', 'RANDO001', '2025-01-20', '2025-01-15', NULL),
(2, 'VAC001', 'RAFT002', '2025-01-21', '2025-01-15', NULL),
(3, 'VAC002', 'RANDO001', '2025-01-20', '2025-01-16', NULL),
(4, 'VAC002', 'ESCAL003', '2025-01-22', '2025-01-16', NULL),
(5, 'VAC003', 'RANDO001', '2025-01-20', '2025-01-17', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `type_anim`
--

CREATE TABLE `type_anim` (
  `CODETYPEANIM` char(5) COLLATE utf8mb4_general_ci NOT NULL,
  `NOMTYPEANIM` char(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_anim`
--

INSERT INTO `type_anim` (`CODETYPEANIM`, `NOMTYPEANIM`) VALUES
('CULT', 'Culture'),
('ESCAL', 'Escalade'),
('RAFT', 'Rafting'),
('RAND', 'Randonnée'),
('SKI', 'Ski');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`CODEANIM`,`DATEACT`),
  ADD KEY `I_FK_ACTIVITE_ANIMATION` (`CODEANIM`),
  ADD KEY `I_FK_ACTIVITE_ETAT_ACT` (`CODEETATACT`);

--
-- Index pour la table `animation`
--
ALTER TABLE `animation`
  ADD PRIMARY KEY (`CODEANIM`),
  ADD KEY `I_FK_ANIMATION_TYPE_ANIM` (`CODETYPEANIM`);

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`USER`);

--
-- Index pour la table `etat_act`
--
ALTER TABLE `etat_act`
  ADD PRIMARY KEY (`CODEETATACT`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`NOINSCRIP`),
  ADD KEY `I_FK_INSCRIPTION_COMPTE` (`USER`),
  ADD KEY `I_FK_INSCRIPTION_ACTIVITE` (`CODEANIM`,`DATEACT`);

--
-- Index pour la table `type_anim`
--
ALTER TABLE `type_anim`
  ADD PRIMARY KEY (`CODETYPEANIM`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `NOINSCRIP` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `activite_ibfk_1` FOREIGN KEY (`CODEANIM`) REFERENCES `animation` (`CODEANIM`),
  ADD CONSTRAINT `activite_ibfk_2` FOREIGN KEY (`CODEETATACT`) REFERENCES `etat_act` (`CODEETATACT`);

--
-- Contraintes pour la table `animation`
--
ALTER TABLE `animation`
  ADD CONSTRAINT `animation_ibfk_1` FOREIGN KEY (`CODETYPEANIM`) REFERENCES `type_anim` (`CODETYPEANIM`);

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`USER`) REFERENCES `compte` (`USER`),
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`CODEANIM`,`DATEACT`) REFERENCES `activite` (`CODEANIM`, `DATEACT`);
COMMIT;

