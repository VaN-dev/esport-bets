-- phpMyAdmin SQL Dump
-- version 4.6.4deb1
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 27 Mars 2017 à 20:46
-- Version du serveur :  5.7.17
-- Version de PHP :  7.0.15-0ubuntu0.16.10.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `esport-bets`
--
CREATE DATABASE IF NOT EXISTS `esport-bets` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `esport-bets`;

-- --------------------------------------------------------

--
-- Structure de la table `ebets_bets`
--

DROP TABLE IF EXISTS `ebets_bets`;
CREATE TABLE `ebets_bets` (
  `id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `noticed` tinyint(1) NOT NULL DEFAULT '0',
  `datetime` datetime NOT NULL,
  `opponent_id` int(11) NOT NULL,
  `odds_id` int(11) NOT NULL,
  `stake` float NOT NULL,
  `statut` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ebets_comments`
--

DROP TABLE IF EXISTS `ebets_comments`;
CREATE TABLE `ebets_comments` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `match_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ebets_config`
--

DROP TABLE IF EXISTS `ebets_config`;
CREATE TABLE `ebets_config` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `site_url` varchar(255) NOT NULL,
  `site_short_url` varchar(255) NOT NULL,
  `site_title` varchar(255) NOT NULL,
  `site_description` text NOT NULL,
  `site_keywords` varchar(255) NOT NULL,
  `site_default_lang` varchar(255) NOT NULL,
  `site_build_xml` varchar(255) NOT NULL,
  `site_maintenance` tinyint(1) NOT NULL,
  `site_maintenance_message` text NOT NULL,
  `contact_mail` varchar(255) NOT NULL,
  `contact_tel` varchar(255) NOT NULL,
  `contact_fax` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `company_zipcode` varchar(255) NOT NULL,
  `company_city` varchar(255) NOT NULL,
  `company_country` varchar(255) NOT NULL,
  `company_legal_status` varchar(255) NOT NULL,
  `company_capital` varchar(255) NOT NULL,
  `company_siren` varchar(255) NOT NULL,
  `company_responsable` varchar(255) NOT NULL,
  `analytics_key` varchar(255) NOT NULL,
  `map_key` varchar(255) NOT NULL,
  `map_latitude` varchar(255) NOT NULL,
  `map_longitude` varchar(255) NOT NULL,
  `social_facebook` varchar(255) NOT NULL,
  `social_twitter` varchar(255) NOT NULL,
  `layout_carousel_homepage_type` varchar(255) NOT NULL,
  `layout_carousel_homepage_id` varchar(255) NOT NULL,
  `layout_carousel_galeries` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ebets_games`
--

DROP TABLE IF EXISTS `ebets_games`;
CREATE TABLE `ebets_games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ebets_games`
--

INSERT INTO `ebets_games` (`id`, `name`, `icon`) VALUES
(1, 'League of Legends', 'icon-lol.png');

-- --------------------------------------------------------

--
-- Structure de la table `ebets_matches`
--

DROP TABLE IF EXISTS `ebets_matches`;
CREATE TABLE `ebets_matches` (
  `id` int(11) NOT NULL,
  `game_id` tinyint(4) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL,
  `featured` tinyint(1) NOT NULL,
  `statut` tinyint(4) NOT NULL,
  `winner_id` int(11) NOT NULL,
  `banner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ebets_matches`
--

INSERT INTO `ebets_matches` (`id`, `game_id`, `start`, `end`, `featured`, `statut`, `winner_id`, `banner`) VALUES
(1, 1, '2017-03-31 00:00:00', '2017-03-31 02:00:00', 1, 1, 0, 'eg_vs_gg_2.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `ebets_odds`
--

DROP TABLE IF EXISTS `ebets_odds`;
CREATE TABLE `ebets_odds` (
  `id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `opponent_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `value` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ebets_odds`
--

INSERT INTO `ebets_odds` (`id`, `datetime`, `opponent_id`, `match_id`, `value`) VALUES
(1, '2017-03-21 00:00:00', 1, 1, 2.5),
(2, '2017-03-21 00:00:00', 2, 1, 1.3);

-- --------------------------------------------------------

--
-- Structure de la table `ebets_opponents`
--

DROP TABLE IF EXISTS `ebets_opponents`;
CREATE TABLE `ebets_opponents` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ebets_opponents`
--

INSERT INTO `ebets_opponents` (`id`, `name`, `image`) VALUES
(1, 'Evil Geniuses', 'evil-geniuses.jpg'),
(2, 'Gambit Gaming', 'gambit-gaming.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `ebets_users`
--

DROP TABLE IF EXISTS `ebets_users`;
CREATE TABLE `ebets_users` (
  `id` int(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `password` varchar(255) NOT NULL,
  `activation_key` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `points` float NOT NULL,
  `last_bet_datetime` datetime DEFAULT NULL,
  `statut` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ebets_users`
--

INSERT INTO `ebets_users` (`id`, `mail`, `datetime`, `password`, `activation_key`, `firstname`, `birthdate`, `points`, `last_bet_datetime`, `statut`) VALUES
(1, 'fanel.dev@gmail.com', '2017-03-21 21:19:15', 'b0e78e25f2a5599ecba2fe159e012546e47d62ff', 'lJCYZawjwf', 'Fanel', '1983-03-29', 100, NULL, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `ebets_bets`
--
ALTER TABLE `ebets_bets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ebets_comments`
--
ALTER TABLE `ebets_comments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ebets_config`
--
ALTER TABLE `ebets_config`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ebets_games`
--
ALTER TABLE `ebets_games`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ebets_matches`
--
ALTER TABLE `ebets_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Index pour la table `ebets_odds`
--
ALTER TABLE `ebets_odds`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ebets_opponents`
--
ALTER TABLE `ebets_opponents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ebets_users`
--
ALTER TABLE `ebets_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `ebets_bets`
--
ALTER TABLE `ebets_bets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ebets_comments`
--
ALTER TABLE `ebets_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ebets_config`
--
ALTER TABLE `ebets_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ebets_games`
--
ALTER TABLE `ebets_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `ebets_matches`
--
ALTER TABLE `ebets_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `ebets_odds`
--
ALTER TABLE `ebets_odds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `ebets_opponents`
--
ALTER TABLE `ebets_opponents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `ebets_users`
--
ALTER TABLE `ebets_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
