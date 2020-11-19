-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 nov. 2020 à 14:17
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boutique`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  `etat` enum('en_cours_de_traitrement','envoye','livre') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_membre`, `montant`, `date_enregistrement`, `etat`) VALUES
(5, 1, 280, '2020-11-09 14:55:05', 'en_cours_de_traitrement'),
(6, 1, 280, '2020-11-09 15:00:50', 'en_cours_de_traitrement'),
(7, 2, 480, '2020-11-09 15:24:30', 'en_cours_de_traitrement'),
(8, 1, 105, '2020-11-09 15:28:05', 'en_cours_de_traitrement');

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_details_commande` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite` int(5) NOT NULL,
  `prix` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `details_commande`
--

INSERT INTO `details_commande` (`id_details_commande`, `id_commande`, `id_produit`, `quantite`, `prix`) VALUES
(4, 5, 30, 3, 50),
(5, 5, 32, 4, 25),
(6, 5, 34, 1, 30),
(7, 6, 30, 3, 50),
(8, 6, 32, 4, 25),
(9, 6, 34, 1, 30),
(10, 7, 30, 7, 50),
(11, 7, 32, 4, 25),
(12, 7, 34, 1, 30),
(13, 8, 33, 3, 15),
(14, 8, 34, 2, 30);

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id_membre` int(11) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('homme','femme') NOT NULL,
  `ville` varchar(20) NOT NULL,
  `code_postal` int(5) UNSIGNED ZEROFILL NOT NULL,
  `adresse` text NOT NULL,
  `statut` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `ville`, `code_postal`, `adresse`, `statut`) VALUES
(1, 'Lyly', '$2y$10$rCeGPsUibK3KlRVE3TEO.OM66pmvh8KuD.kRKVQe29U4fGdB0z8ZO', 'Diomande', 'Sally', 'sallydiomande@gmail.com', 'femme', 'Les Mureaux', 78130, '45 rue des giroflés', 1),
(2, 'Lass78', '$2y$10$R4TOsurovznNcMW6fAr0Fu/IZvsMddNYWgPXtYcLYArnII5Fo.S3K', 'Camara', 'Lassana', 'lasscam@gmail.com', 'homme', 'Les Mureaux', 78130, '45 avenue de la République', 0),
(3, 'lyda', '$2y$10$/.EEE4gdjOBO.BkH8VnPFe/GMY.faYsFrfAkFhle.hYs35Z8dvujC', 'Konte', 'Daly', 'kontedaly@gmail.com', 'femme', 'Les Mureaux', 78130, '30 avenue de la République', 0),
(5, 'titi75', '$2y$10$UskF2JuIao0csSZ7akRDZey3Pm1MlrBkX0DEn3eL8YqbCyEzR4FeO', 'Beau', 'Tristan', 'beautristan@gmail.com', 'homme', 'Paris', 75013, '23 rue du docteur Landouzy', 1);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `categorie` varchar(20) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `couleur` varchar(20) NOT NULL,
  `taille` varchar(5) NOT NULL,
  `public` enum('homme','femme','mixte') NOT NULL,
  `photo` varchar(250) NOT NULL,
  `prix` int(5) NOT NULL,
  `stock` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `reference`, `categorie`, `titre`, `description`, `couleur`, `taille`, `public`, `photo`, `prix`, `stock`) VALUES
(29, '15A45', 'Tee-shirt', 'Tee-shirt bleu', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi aut ducimus optio et saepe minima quibusdam reiciendis impedit, quae totam unde at accusamus natus eum adipisci recusandae nostrum nisi ratione delectus. Nihil ab eius officiis illum aliquam obcaecati velit non, omnis delectus, voluptate consequatur laboriosam architecto quam ad itaque modi!\r\n', 'Bleu', 'm', 'homme', 'http://localhost/PHP/09-boutique/photo/15A45-15A45-tee-shirt-4.jpg', 15, 0),
(30, '45A78', 'Pull', 'Pull vert', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi aut ducimus optio et saepe minima quibusdam reiciendis impedit, quae totam unde at accusamus natus eum adipisci recusandae nostrum nisi ratione delectus. Nihil ab eius officiis illum aliquam obcaecati velit non, omnis delectus, voluptate consequatur laboriosam architecto quam ad itaque modi!\r\n', 'vert', 'm', 'homme', 'http://localhost/PHP/09-boutique/photo/45A78-45A78-tee-shirt-6.jpg', 50, 10),
(31, '19L56', 'Chemise', 'Chemise noir', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi aut ducimus optio et saepe minima quibusdam reiciendis impedit, quae totam unde at accusamus natus eum adipisci recusandae nostrum nisi ratione delectus. Nihil ab eius officiis illum aliquam obcaecati velit non, omnis delectus, voluptate consequatur laboriosam architecto quam ad itaque modi!\r\n', 'noir', 'xl', 'homme', 'http://localhost/PHP/09-boutique/photo/19L56-19L56-tee-shirt-5.jpg', 30, 150),
(32, '23P45', 'Tee-shirt', 'Tee-shirt violet', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi aut ducimus optio et saepe minima quibusdam reiciendis impedit, quae totam unde at accusamus natus eum adipisci recusandae nostrum nisi ratione delectus. Nihil ab eius officiis illum aliquam obcaecati velit non, omnis delectus, voluptate consequatur laboriosam architecto quam ad itaque modi!\r\n', 'violet', 'l', 'homme', 'http://localhost/PHP/09-boutique/photo/23P45-23P45-tee-shirt-6.jpg', 25, 67),
(33, '19S73', 'Pull', 'Pull marron', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi aut ducimus optio et saepe minima quibusdam reiciendis impedit, quae totam unde at accusamus natus eum adipisci recusandae nostrum nisi ratione delectus. Nihil ab eius officiis illum aliquam obcaecati velit non, omnis delectus, voluptate consequatur laboriosam architecto quam ad itaque modi!\r\n', 'marron', 'm', 'homme', 'http://localhost/PHP/09-boutique/photo/19S73-19S73-tee-shirt-3.jpg', 15, 90),
(34, '12B09', 'Robe', 'Robe noir', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi aut ducimus optio et saepe minima quibusdam reiciendis impedit, quae totam unde at accusamus natus eum adipisci recusandae nostrum nisi ratione delectus. Nihil ab eius officiis illum aliquam obcaecati velit non, omnis delectus, voluptate consequatur laboriosam architecto quam ad itaque modi!\r\n', 'noir', 's', 'femme', 'http://localhost/PHP/09-boutique/photo/12B09-12B09-robe-1.jpg', 30, 106);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_details_commande`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id_membre`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_details_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `membre`
--
ALTER TABLE `membre`
  MODIFY `id_membre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
