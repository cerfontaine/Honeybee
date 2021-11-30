-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost
-- Généré le :  Mar 02 Juin 2020 à 23:41
-- Version du serveur :  5.7.29
-- Version de PHP :  5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `in19b1015`
--

-- --------------------------------------------------------

--
-- Structure de la table `collector_categorie`
--

CREATE TABLE `collector_categorie` (
  `id_categorie` int(11) NOT NULL,
  `categorie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collector_categorie`
--

INSERT INTO `collector_categorie` (`id_categorie`, `categorie`) VALUES
(1, 'Art'),
(2, 'Bande dessinée'),
(3, 'Design'),
(4, 'Film et vidéos'),
(5, 'Jeux'),
(6, 'Mode'),
(7, 'Musique'),
(8, 'Solidaire'),
(9, 'Sports'),
(11, 'Technologie');

-- --------------------------------------------------------

--
-- Structure de la table `collector_commentaire`
--

CREATE TABLE `collector_commentaire` (
  `id_comment` int(11) NOT NULL,
  `commentaire` varchar(500) NOT NULL,
  `date_modification` date NOT NULL,
  `est_supprime` tinyint(1) NOT NULL,
  `id_projet` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collector_commentaire`
--

INSERT INTO `collector_commentaire` (`id_comment`, `commentaire`, `date_modification`, `est_supprime`, `id_projet`, `id_membre`) VALUES
(2, 'G&eacute;nial, je peux venir ? ', '2020-05-18', 1, 2, 9),
(5, 'G&eacute;nial ce projet !!! ', '2020-05-18', 1, 4, 9),
(30, 'G&eacute;nial ce projet ! Bien que la description soit un peu &eacute;vasive ! ', '2020-05-19', 1, 4, 9),
(31, 'jfjfjffj', '2020-05-19', 1, 4, 9),
(33, '&lt;script&gt;alert(\'TEST\');&lt;/script&gt;', '2020-05-21', 1, 2, 9),
(34, '&amp;lt;script&amp;gt;alert(&amp;#x27;TEST&amp;#x27; );&amp;lt;&amp;#x2F;script&amp;gt;', '2020-05-21', 1, 2, 9),
(39, 'Ce projet a vraiment l\'air g&eacute;nial ! Merci Flolec', '2020-06-02', 0, 4, 21),
(40, 'IFT qui trashtalk Samsung ? On aura tout vu !\r\n', '2020-06-02', 0, 7, 21),
(41, 'IFT fait ce qu\'il veut ', '2020-06-02', 0, 7, 20);

-- --------------------------------------------------------

--
-- Structure de la table `collector_membre`
--

CREATE TABLE `collector_membre` (
  `id_membre` int(11) NOT NULL,
  `login` varchar(300) NOT NULL,
  `prenom` varchar(300) NOT NULL,
  `nom` varchar(300) NOT NULL,
  `mot_passe` text NOT NULL,
  `courriel` varchar(255) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `adresse_rue` varchar(50) NOT NULL,
  `adresse_num` varchar(50) NOT NULL,
  `adresse_code` varchar(50) NOT NULL,
  `adresse_ville` varchar(50) NOT NULL,
  `adresse_pays` varchar(50) NOT NULL,
  `avatar` text NOT NULL,
  `carte_VISA` varchar(50) NOT NULL,
  `est_desactive` tinyint(1) NOT NULL,
  `est_admin` tinyint(1) NOT NULL,
  `last_seen` date,
  `login_status` tinyint(1)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collector_membre`
--

INSERT INTO `collector_membre` (`id_membre`, `login`, `prenom`, `nom`, `mot_passe`, `courriel`, `tel`, `adresse_rue`, `adresse_num`, `adresse_code`, `adresse_ville`, `adresse_pays`, `avatar`, `carte_VISA`, `est_desactive`, `est_admin`) VALUES
(7, 'Finchator', 'Harold', 'Finch', '09e9b78f9dfeb27f0c94bea4389d3376adabd6df45aefa89d7ef336fba04f675db01a6abf55479d9addef765d22a30dafc899f97f098b5ec5b13077b786541e5', 'harold.finch@ift.com', '0492878358', 'Rue de la limite 188', '45', '4040', 'Herstal', 'Belgique', '5e9215ba11ed4.jpg', '0123456789101112', 0, 0),
(9, 'admin', 'Admin', 'Jean', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 'admin@gmail.com', '0479652531', 'Rue des paquerettes', '4515', '4651', 'AdminCity', 'AdminLand', '5ed100a783187.jpg', '1234567812345670', 0, 1),
(10, 'florenceleclercq', 'Florence', 'LECLERCQ', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'florence.leclercq@florence.be', '123', 'des champs', '66', '4000', 'lg', 'be', '5e9acef290db2.png', '4012001037141119', 0, 0),
(11, 'Flolec', 'Florence', 'LECLERCQ', '3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', 'f.leclercq@helmo.be', '042231131', 'des champs', '66', '4000', 'Li&egrave;ge', 'Belgium', '5e9ad0a48af2e.png', '4012001037141112', 0, 0),
(20, 'iftech', 'Technologies', 'IFT', '95073cb5ff16fd6089a88fa48f829f798fc39ca638b2ff16cf796342ff73d29e42637f054a395baf573ff2df11e953485e610c98590a9db1d1947df24fa901ca', 'sibilancetech@gmail.com', ' 213-509-6995', 'Rue de l\'informatique', '46', '4000', 'Li&egrave;ge', 'Belgique', '5ed6b4e403fb0.jpg', '1234567812345670', 0, 0),
(21, 'johndoe', 'John', 'Doe', 'cbda1c52153b137c8543bec07a3eccd5a478c73930bbe46167e02abd1faf570bd6651890913a49574c64c70f6924e4075309da7a76b3ae7a94673c47a1086ba5', 'johndoe785@yopmail.com', '1010101010101010', 'Rue du Doe', '45', '4856', 'DoeVille', 'Belgique', '5ed6bc37b4cf1.png', '1234567812345670', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `collector_news`
--

CREATE TABLE `collector_news` (
  `id_news` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `url` text,
  `date_publication` date NOT NULL,
  `id_projet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collector_news`
--

INSERT INTO `collector_news` (`id_news`, `intitule`, `description`, `url`, `date_publication`, `id_projet`) VALUES
(9, 'Couverture de la BD ', 'En exclu la couv de la BD by me ! ', '5ed6be8919b5a.png', '2020-06-02', 9),
(10, 'Info m&eacute;moire', 'Le t&eacute;l&eacute;phone aura une m&eacute;moire de 512 go et 16 go de ram ! Une dinguerie', '', '2020-06-02', 7);

-- --------------------------------------------------------

--
-- Structure de la table `collector_participation`
--

CREATE TABLE `collector_participation` (
  `id_participation` int(11) NOT NULL,
  `date_participation` date NOT NULL,
  `montant` decimal(20,2) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collector_participation`
--

INSERT INTO `collector_participation` (`id_participation`, `date_participation`, `montant`, `id_membre`, `id_projet`) VALUES
(19, '2020-05-20', '4502.00', 9, 2),
(23, '2020-06-02', '8.00', 21, 4),
(24, '2020-06-02', '20.00', 9, 9);

-- --------------------------------------------------------

--
-- Structure de la table `collector_projet`
--

CREATE TABLE `collector_projet` (
  `id_projet` int(11) NOT NULL,
  `intitule` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_echeance` date NOT NULL,
  `date_creation` date NOT NULL,
  `montant` decimal(20,2) NOT NULL,
  `montant_min` decimal(20,0) NOT NULL,
  `illustration_apercu` varchar(50) NOT NULL,
  `carte_visa` varchar(50) NOT NULL,
  `nom_visa` varchar(50) NOT NULL,
  `est_prolonge` tinyint(1) NOT NULL,
  `est_valide` tinyint(1) DEFAULT NULL,
  `taux_participation` decimal(15,0) DEFAULT '0',
  `id_membre` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_type_part` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collector_projet`
--

INSERT INTO `collector_projet` (`id_projet`, `intitule`, `description`, `date_echeance`, `date_creation`, `montant`, `montant_min`, `illustration_apercu`, `carte_visa`, `nom_visa`, `est_prolonge`, `est_valide`, `taux_participation`, `id_membre`, `id_categorie`, `id_type_part`) VALUES
(2, 'Piratage de la Nasa', 'Action de pirater, c\'est-à-dire d\'aborder un navire ou un outil informatique, sans en demander l\'autorisation, de manière illégale, pour en voler le contenu.\r\nExemple : Le piratage informatique est un fléau de plus en plus répandu.', '2020-04-25', '2020-04-12', '450.00', '2', '5e9379a5ef14c.png', '0123456789101112', 'hubert', 0, NULL, '1000', 7, 3, 3),
(4, 'LA BD en folie', ': Il n\'y a pas de signe de r&eacute;f&eacute;rence dans l\'appel de la fonction, uniquement sur sa d&eacute;finition. La d&eacute;finition de la fonction en elle-m&ecirc;me est suffisante pour passer correctement des arguments par r&eacute;f&eacute;rence. A partir de PHP 5.3.0, vous devriez recevoir une alerte disant que &quot;Call-time pass-by-reference&quot; est obsol&egrave;te, lorsque vous utilisez un &amp; dans foo(&amp;$a);. Et &agrave; partir de PHP 5.4.0, call-time pass-by-reference a &eacute;t&eacute; supprim&eacute;, l\'utiliser l&egrave;vera une erreur fatale.', '2021-01-01', '2020-04-18', '10.00', '2', '5e9ae2855bff4.png', '4012001037141112', 'moi', 0, NULL, '80', 11, 2, 1),
(7, 'Le t&eacute;l&eacute;phone foldable', 'Le nouveau t&eacute;l&eacute;phone de chez IFT risque de faire des jaloux !! Ce t&eacute;l&eacute;phone pliable ou &quot;foldable&quot; est con&ccedil;u pour ne casser comme celui de chez Sam****. De part son &eacute;cran tr&egrave;s qualitatif, vos yeux auront un confort incroyablement incroyable !', '2020-07-31', '2020-06-02', '450000.00', '2', '5ed6b6dfb0c99.jpg', '1234567812345670', 'IFT', 0, NULL, '0', 20, 11, 1),
(8, 'Le v&eacute;lo volant', 'Sibilance vous pr&eacute;sente son prototype ultra l&eacute;ger de v&eacute;lo volant ! Ce dernier fonctionne &agrave; l\'&eacute;nergie nucl&eacute;aire et a une autonomie de 286 000 km !! Avec une vitesse de croisi&egrave;re approchant les 340 km/h, ce petit bijou de technologie sera accessible &agrave; tous les d&eacute;tenteurs du permis B Master Aviation.', '2020-08-31', '2020-06-02', '78000.00', '2', '5ed6b96f36d41.png', '1234567812345670', 'IFT', 0, NULL, '0', 20, 11, 1),
(9, 'Blacksad Tome 6 et 7', 'Blacksad est une s&eacute;rie de bande dessin&eacute;e polici&egrave;re et animali&egrave;re en cinq albums, de Juan D&iacute;az Canales et Juanjo Guarnido.\r\n\r\nLes histoires prennent place dans une atmosph&egrave;re de film noir, aux &Eacute;tats-Unis dans les ann&eacute;es 1950. Tous les personnages sont des animaux anthropomorphes dont l&rsquo;esp&egrave;ce refl&egrave;te le caract&egrave;re ainsi que le r&ocirc;le dans l&rsquo;histoire. Le h&eacute;ros, John Blacksad, est un chat noir &agrave; museau blanc exer&ccedil;ant comme d&eacute;tective priv&eacute;.\r\n\r\nL&rsquo;atmosph&egrave;re sombre de polar est autant rendue par le graphisme que par un jeu de voix off, de r&eacute;pliques et de silences expressifs typ&eacute;s. La coloration &agrave; l&rsquo;aquarelle, ainsi que l&rsquo;influence du travail de Juanjo Guarnido dans l&rsquo;animation aux studios Disney, donnent une r&eacute;elle impression de mouvement.', '2020-07-25', '2020-06-02', '450.00', '1', '5ed6bcf42b334.jpg', '1234567812345670', 'John', 0, NULL, '4', 21, 2, 1),
(10, 'La montre pas connect&eacute;e', 'Cette montre non connect&eacute;e est fabriqu&eacute;e en chine mais on vous fera croire que nous sommes horloger depuis 6 g&eacute;n&eacute;rations et que cette montre vaut 12 000&euro; ! Et vous savez quoi ? Vous allez l\'acheter !', '2020-07-31', '2020-06-02', '1250000.00', '50', '5ed6c5057c24f.png', '1234567812345670', 'Admin', 0, NULL, '0', 9, 6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `collector_seuil_recompense`
--

CREATE TABLE `collector_seuil_recompense` (
  `id_seuil` int(11) NOT NULL,
  `montant` decimal(20,2) NOT NULL,
  `contrepartie` varchar(255) DEFAULT NULL,
  `id_type_part` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `collector_type_participation`
--

CREATE TABLE `collector_type_participation` (
  `id_type_part` int(11) NOT NULL,
  `libelle` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collector_type_participation`
--

INSERT INTO `collector_type_participation` (`id_type_part`, `libelle`) VALUES
(1, 'Le don'),
(2, 'La récompense'),
(3, 'Le prêt '),
(4, 'La prise de capital');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `collector_categorie`
--
ALTER TABLE `collector_categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `collector_commentaire`
--
ALTER TABLE `collector_commentaire`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_projet` (`id_projet`),
  ADD KEY `id_membre` (`id_membre`);

--
-- Index pour la table `collector_membre`
--
ALTER TABLE `collector_membre`
  ADD PRIMARY KEY (`id_membre`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Index pour la table `collector_news`
--
ALTER TABLE `collector_news`
  ADD PRIMARY KEY (`id_news`),
  ADD KEY `id_projet` (`id_projet`);

--
-- Index pour la table `collector_participation`
--
ALTER TABLE `collector_participation`
  ADD PRIMARY KEY (`id_participation`),
  ADD KEY `id_membre` (`id_membre`),
  ADD KEY `id_projet` (`id_projet`);

--
-- Index pour la table `collector_projet`
--
ALTER TABLE `collector_projet`
  ADD PRIMARY KEY (`id_projet`),
  ADD KEY `id_membre` (`id_membre`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_type_part` (`id_type_part`);

--
-- Index pour la table `collector_seuil_recompense`
--
ALTER TABLE `collector_seuil_recompense`
  ADD PRIMARY KEY (`id_seuil`),
  ADD KEY `id_type_part` (`id_type_part`),
  ADD KEY `id_projet` (`id_projet`);

--
-- Index pour la table `collector_type_participation`
--
ALTER TABLE `collector_type_participation`
  ADD PRIMARY KEY (`id_type_part`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `collector_categorie`
--
ALTER TABLE `collector_categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `collector_commentaire`
--
ALTER TABLE `collector_commentaire`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT pour la table `collector_membre`
--
ALTER TABLE `collector_membre`
  MODIFY `id_membre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT pour la table `collector_news`
--
ALTER TABLE `collector_news`
  MODIFY `id_news` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `collector_participation`
--
ALTER TABLE `collector_participation`
  MODIFY `id_participation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT pour la table `collector_projet`
--
ALTER TABLE `collector_projet`
  MODIFY `id_projet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `collector_seuil_recompense`
--
ALTER TABLE `collector_seuil_recompense`
  MODIFY `id_seuil` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `collector_type_participation`
--
ALTER TABLE `collector_type_participation`
  MODIFY `id_type_part` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `collector_commentaire`
--
ALTER TABLE `collector_commentaire`
  ADD CONSTRAINT `collector_commentaire_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `collector_projet` (`id_projet`),
  ADD CONSTRAINT `collector_commentaire_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `collector_membre` (`id_membre`);

--
-- Contraintes pour la table `collector_news`
--
ALTER TABLE `collector_news`
  ADD CONSTRAINT `collector_news_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `collector_projet` (`id_projet`);

--
-- Contraintes pour la table `collector_participation`
--
ALTER TABLE `collector_participation`
  ADD CONSTRAINT `collector_participation_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `collector_membre` (`id_membre`),
  ADD CONSTRAINT `collector_participation_ibfk_2` FOREIGN KEY (`id_projet`) REFERENCES `collector_projet` (`id_projet`);

--
-- Contraintes pour la table `collector_projet`
--
ALTER TABLE `collector_projet`
  ADD CONSTRAINT `collector_projet_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `collector_membre` (`id_membre`),
  ADD CONSTRAINT `collector_projet_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `collector_categorie` (`id_categorie`),
  ADD CONSTRAINT `collector_projet_ibfk_3` FOREIGN KEY (`id_type_part`) REFERENCES `collector_type_participation` (`id_type_part`);

--
-- Contraintes pour la table `collector_seuil_recompense`
--
ALTER TABLE `collector_seuil_recompense`
  ADD CONSTRAINT `collector_seuil_recompense_ibfk_1` FOREIGN KEY (`id_type_part`) REFERENCES `collector_type_participation` (`id_type_part`),
  ADD CONSTRAINT `collector_seuil_recompense_ibfk_2` FOREIGN KEY (`id_projet`) REFERENCES `collector_projet` (`id_projet`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
