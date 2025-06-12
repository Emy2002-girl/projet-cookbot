-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 11 juin 2025 à 15:59
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cookbot_recipes`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserFavorites` (IN `p_user_id` INT)   BEGIN
    SELECT r.* 
    FROM RECETTE r
    JOIN FAVORI f ON r.ID_RECETTE = f.ID_RECETTE
    WHERE f.ID_UTILISATEUR = p_user_id
    ORDER BY f.DATE_FAVORI DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SearchRecipesByIngredients` (IN `p_ingredients` TEXT, IN `p_ustensiles` TEXT, IN `p_duree` INT, IN `p_type_repas` VARCHAR(50), IN `p_niveau` VARCHAR(50))   BEGIN
    DECLARE sql_query TEXT;
    
    SET sql_query = 'SELECT DISTINCT r.* FROM RECETTE r 
                     JOIN RECETTE_INGREDIENT ri ON r.ID_RECETTE = ri.ID_RECETTE 
                     JOIN INGREDIENT i ON ri.ID_INGREDIENT = i.ID_INGREDIENT 
                     WHERE 1=1';
    
    IF p_ingredients IS NOT NULL AND p_ingredients != '' THEN
        SET sql_query = CONCAT(sql_query, ' AND i.NOM IN (', p_ingredients, ')');
    END IF;
    
    IF p_type_repas IS NOT NULL AND p_type_repas != '' THEN
        SET sql_query = CONCAT(sql_query, ' AND r.TYPE_REPAS = "', p_type_repas, '"');
    END IF;
    
    IF p_duree > 0 THEN
        SET sql_query = CONCAT(sql_query, ' AND (r.TEMPS_PREPARATION + r.TEMPS_CUISSON) <= ', p_duree);
    END IF;
    
    -- Filtrage par niveau
    IF p_niveau = 'debutant' THEN
        SET sql_query = CONCAT(sql_query, ' AND r.DIFFICULTE = "Novice"');
    ELSEIF p_niveau = 'intermediaire' THEN
        SET sql_query = CONCAT(sql_query, ' AND r.DIFFICULTE IN ("Novice", "Intermédiaire")');
    ELSEIF p_niveau = 'avance' THEN
        SET sql_query = CONCAT(sql_query, ' AND r.DIFFICULTE IN ("Novice", "Intermédiaire", "Expert")');
    END IF;
    
    SET sql_query = CONCAT(sql_query, ' GROUP BY r.ID_RECETTE 
              ORDER BY r.TEMPS_PREPARATION ASC LIMIT 10');
    
    SET @sql = sql_query;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

CREATE TABLE `abonnement` (
  `ID_ABONNEMENT` int(11) NOT NULL,
  `TYPE_ABONNE` varchar(50) DEFAULT NULL,
  `STATUS` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`ID_ABONNEMENT`, `TYPE_ABONNE`, `STATUS`) VALUES
(1, 'Gratuit', 'Actif'),
(2, 'Premium', 'Actif'),
(3, 'Pro', 'Actif');

-- --------------------------------------------------------

--
-- Structure de la table `favori`
--

CREATE TABLE `favori` (
  `ID_RECETTE` int(11) NOT NULL,
  `ID_UTILISATEUR` int(11) NOT NULL,
  `DATE_FAVORI` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `favori`
--

INSERT INTO `favori` (`ID_RECETTE`, `ID_UTILISATEUR`, `DATE_FAVORI`) VALUES
(1, 3, '2025-06-05 12:37:09'),
(3, 3, '2025-06-05 12:37:09'),
(5, 3, '2025-06-05 12:37:09'),
(7, 2, '2025-06-05 12:37:09');

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

CREATE TABLE `ingredient` (
  `ID_INGREDIENT` int(11) NOT NULL,
  `NOM` varchar(100) NOT NULL,
  `CATEGORIE` varchar(50) DEFAULT NULL,
  `UNITE_MESURE` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ingredient`
--

INSERT INTO `ingredient` (`ID_INGREDIENT`, `NOM`, `CATEGORIE`, `UNITE_MESURE`) VALUES
(1, 'Tomate', 'Légume', 'pièce'),
(2, 'Oignon', 'Légume', 'pièce'),
(3, 'Ail', 'Légume', 'gousse'),
(4, 'Carotte', 'Légume', 'pièce'),
(5, 'Pomme de terre', 'Légume', 'pièce'),
(6, 'Courgette', 'Légume', 'pièce'),
(7, 'Aubergine', 'Légume', 'pièce'),
(8, 'Poivron rouge', 'Légume', 'pièce'),
(9, 'Champignons', 'Légume', 'gramme'),
(10, 'Épinards', 'Légume', 'gramme'),
(11, 'Haricots verts', 'Légume', 'gramme'),
(12, 'Œuf', 'Protéine', 'pièce'),
(13, 'Poulet', 'Protéine', 'gramme'),
(14, 'Bœuf haché', 'Protéine', 'gramme'),
(15, 'Thon en conserve', 'Protéine', 'boîte'),
(16, 'Saumon', 'Protéine', 'gramme'),
(17, 'Crevettes', 'Protéine', 'gramme'),
(18, 'Riz', 'Féculent', 'gramme'),
(19, 'Pâtes', 'Féculent', 'gramme'),
(20, 'Farine', 'Féculent', 'gramme'),
(21, 'Quinoa', 'Féculent', 'gramme'),
(22, 'Lentilles', 'Féculent', 'gramme'),
(23, 'Pain', 'Féculent', 'tranche'),
(24, 'Lait', 'Produit laitier', 'ml'),
(25, 'Beurre', 'Produit laitier', 'gramme'),
(26, 'Fromage râpé', 'Produit laitier', 'gramme'),
(27, 'Yaourt nature', 'Produit laitier', 'gramme'),
(28, 'Mozzarella', 'Produit laitier', 'gramme'),
(29, 'Parmesan', 'Produit laitier', 'gramme'),
(30, 'Cheese', 'Produit laitier', 'gramme'),
(31, 'Citron', 'Fruit', 'pièce'),
(32, 'Avocat', 'Fruit', 'pièce'),
(33, 'Banane', 'Fruit', 'pièce'),
(34, 'Pomme', 'Fruit', 'pièce'),
(35, 'Huile d\'olive', 'Condiment', 'ml'),
(36, 'Sel', 'Épice', 'pincée'),
(37, 'Poivre', 'Épice', 'pincée'),
(38, 'Pepper', 'Épice', 'pincée'),
(39, 'Vinaigre balsamique', 'Condiment', 'ml'),
(40, 'Sauce soja', 'Condiment', 'ml'),
(41, 'Miel', 'Sucrant', 'cuillère à soupe'),
(42, 'Sucre', 'Sucrant', 'gramme');

-- --------------------------------------------------------

--
-- Structure de la table `recette`
--

CREATE TABLE `recette` (
  `ID_RECETTE` int(11) NOT NULL,
  `ID_UTILISATEUR` int(11) DEFAULT NULL,
  `TITRE` varchar(200) NOT NULL,
  `DESCRIPTION` text DEFAULT NULL,
  `INGREDIENTS` text DEFAULT NULL,
  `INSTRUCTIONS` text DEFAULT NULL,
  `TEMPS_PREPARATION` int(11) DEFAULT NULL,
  `TEMPS_CUISSON` int(11) DEFAULT NULL,
  `DIFFICULTE` enum('Novice','Intermédiaire','Expert') DEFAULT 'Novice',
  `TYPE_REPAS` enum('petit-déjeuner','déjeuner','dîner','collation','dessert','apéritif') DEFAULT NULL,
  `DATE_CREATION` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `recette`
--

INSERT INTO `recette` (`ID_RECETTE`, `ID_UTILISATEUR`, `TITRE`, `DESCRIPTION`, `INGREDIENTS`, `INSTRUCTIONS`, `TEMPS_PREPARATION`, `TEMPS_CUISSON`, `DIFFICULTE`, `TYPE_REPAS`, `DATE_CREATION`) VALUES
(1, 1, 'Œufs brouillés simples', 'Des œufs brouillés parfaits pour débuter en cuisine', '3 œufs, 2 cuillères à soupe de lait, sel, poivre, beurre', '1. Cassez les œufs dans un bol.\r\n2. Ajoutez le lait, sel et poivre.\r\n3. Battez le mélange avec un fouet.\r\n4. Faites fondre le beurre dans une poêle à feu moyen.\r\n5. Versez les œufs et remuez doucement avec une spatule.\r\n6. Cuisez 2-3 minutes en remuant constamment.\r\n7. Retirez du feu quand les œufs sont encore légèrement baveux.', 5, 5, 'Novice', 'petit-déjeuner', '2025-06-05 12:37:09'),
(2, 1, 'Sandwich jambon-beurre', 'Un classique français simple à réaliser', '2 tranches de pain, beurre, jambon, salade', '1. Sortez le beurre du réfrigérateur pour qu\'il ramollisse.\r\n2. Beurrez généreusement les deux tranches de pain.\r\n3. Disposez le jambon sur une tranche.\r\n4. Ajoutez quelques feuilles de salade.\r\n5. Fermez le sandwich avec la deuxième tranche.\r\n6. Coupez en diagonale si désiré.', 3, 0, 'Novice', 'déjeuner', '2025-06-05 12:37:09'),
(3, 1, 'Pâtes au beurre et parmesan', 'Des pâtes simples et savoureuses', '200g de pâtes, 30g de beurre, 50g de parmesan râpé, sel, poivre', '1. Faites bouillir une grande casserole d\'eau salée.\r\n2. Ajoutez les pâtes et cuisez selon les instructions du paquet.\r\n3. Pendant ce temps, râpez le parmesan si nécessaire.\r\n4. Égouttez les pâtes en gardant un peu d\'eau de cuisson.\r\n5. Remettez les pâtes dans la casserole.\r\n6. Ajoutez le beurre et mélangez.\r\n7. Ajoutez le parmesan et un peu d\'eau de cuisson si nécessaire.\r\n8. Assaisonnez avec sel et poivre.', 5, 10, 'Novice', 'déjeuner', '2025-06-05 12:37:09'),
(4, 1, 'Salade de tomates simple', 'Une salade fraîche et facile', '4 tomates, huile d\'olive, vinaigre balsamique, sel, poivre', '1. Lavez et coupez les tomates en rondelles.\r\n2. Disposez-les dans un plat.\r\n3. Arrosez d\'huile d\'olive et de vinaigre balsamique.\r\n4. Assaisonnez avec sel et poivre.\r\n5. Laissez reposer 10 minutes avant de servir.', 10, 0, 'Novice', 'déjeuner', '2025-06-05 12:37:09'),
(5, 2, 'Risotto aux champignons', 'Un risotto crémeux traditionnel italien', '300g riz arborio, 1L bouillon de légumes, 200g champignons, 1 oignon, 100ml vin blanc, 50g parmesan, beurre, huile d\'olive', '1. Faites chauffer le bouillon et gardez-le au chaud.\r\n2. Émincez l\'oignon et les champignons.\r\n3. Dans une casserole, faites revenir l\'oignon dans l\'huile d\'olive.\r\n4. Ajoutez le riz et nacrez 2 minutes en remuant.\r\n5. Versez le vin blanc et laissez évaporer.\r\n6. Ajoutez le bouillon louche par louche en remuant constamment.\r\n7. À mi-cuisson, ajoutez les champignons.\r\n8. Continuez à ajouter le bouillon jusqu\'à ce que le riz soit crémeux.\r\n9. Incorporez le parmesan et le beurre hors du feu.', 15, 25, 'Intermédiaire', 'dîner', '2025-06-05 12:37:09'),
(6, 2, 'Poulet rôti aux légumes', 'Un poulet rôti avec des légumes de saison', '1 poulet entier, 4 pommes de terre, 2 carottes, 1 oignon, huile d\'olive, thym, sel, poivre', '1. Préchauffez le four à 200°C.\r\n2. Épluchez et coupez les légumes en gros morceaux.\r\n3. Assaisonnez le poulet avec sel, poivre et thym.\r\n4. Disposez les légumes dans un plat à four.\r\n5. Placez le poulet sur les légumes.\r\n6. Arrosez d\'huile d\'olive.\r\n7. Enfournez pour 1h en arrosant régulièrement.\r\n8. Vérifiez la cuisson avec un thermomètre (75°C à cœur).', 20, 60, 'Intermédiaire', 'dîner', '2025-06-05 12:37:09'),
(7, 2, 'Quiche lorraine', 'Une quiche traditionnelle française', '1 pâte brisée, 200g lardons, 3 œufs, 200ml crème fraîche, 100g fromage râpé, muscade, sel, poivre', '1. Préchauffez le four à 180°C.\r\n2. Étalez la pâte dans un moule à tarte.\r\n3. Piquez le fond avec une fourchette.\r\n4. Faites revenir les lardons dans une poêle.\r\n5. Battez les œufs avec la crème fraîche.\r\n6. Assaisonnez avec sel, poivre et muscade.\r\n7. Répartissez les lardons sur la pâte.\r\n8. Versez l\'appareil à quiche.\r\n9. Parsemez de fromage râpé.\r\n10. Enfournez 35-40 minutes.', 25, 40, 'Intermédiaire', 'déjeuner', '2025-06-05 12:37:09'),
(8, 2, 'Bouillabaisse provençale', 'Une bouillabaisse traditionnelle avec rouille maison', '1kg poissons variés, 500g crevettes, 2 tomates, 1 oignon, 2 gousses d\'ail, safran, fenouil, thym, laurier, huile d\'olive', '1. Préparez le fumet avec les arêtes de poisson.\r\n2. Coupez les poissons en tronçons.\r\n3. Préparez la rouille : ail, jaune d\'œuf, safran, huile d\'olive.\r\n4. Dans une casserole, faites revenir oignon et tomates.\r\n5. Ajoutez les aromates et le safran.\r\n6. Versez le fumet et portez à ébullition.\r\n7. Ajoutez les poissons selon leur temps de cuisson.\r\n8. Servez avec la rouille et des croûtons.', 45, 30, 'Expert', 'dîner', '2025-06-05 12:37:09'),
(9, 2, 'Soufflé au fromage', 'Un soufflé léger et aérien', '40g beurre, 40g farine, 300ml lait, 4 œufs, 100g fromage râpé, muscade, sel, poivre', '1. Préchauffez le four à 180°C.\r\n2. Préparez une béchamel : beurre, farine, lait.\r\n3. Incorporez le fromage râpé à la béchamel.\r\n4. Séparez les blancs des jaunes d\'œufs.\r\n5. Incorporez les jaunes à la béchamel refroidie.\r\n6. Montez les blancs en neige ferme.\r\n7. Incorporez délicatement les blancs en trois fois.\r\n8. Versez dans un moule beurré et fariné.\r\n9. Enfournez 25-30 minutes sans ouvrir le four.', 30, 25, 'Expert', 'dîner', '2025-06-05 12:37:09'),
(10, 2, 'Coq au vin', 'Un grand classique de la cuisine française', '1 coq découpé, 750ml vin rouge, 200g lardons, 12 petits oignons, 250g champignons, 2 carottes, bouquet garni, beurre, farine', '1. Faites mariner le coq dans le vin rouge 24h.\r\n2. Égouttez et réservez la marinade.\r\n3. Faites revenir les lardons et les légumes.\r\n4. Farinez les morceaux de coq et faites-les dorer.\r\n5. Flambez au cognac.\r\n6. Ajoutez la marinade et le bouquet garni.\r\n7. Laissez mijoter 1h30 à feu doux.\r\n8. Liez la sauce avec du beurre manié.\r\n9. Rectifiez l\'assaisonnement.', 40, 90, 'Expert', 'dîner', '2025-06-05 12:37:09');

-- --------------------------------------------------------

--
-- Structure de la table `recette_ingredient`
--

CREATE TABLE `recette_ingredient` (
  `ID_RECETTE` int(11) NOT NULL,
  `ID_INGREDIENT` int(11) NOT NULL,
  `QUANTITE` decimal(10,2) DEFAULT NULL,
  `UNITE` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `recette_ingredient`
--

INSERT INTO `recette_ingredient` (`ID_RECETTE`, `ID_INGREDIENT`, `QUANTITE`, `UNITE`) VALUES
(1, 12, 3.00, 'pièce'),
(1, 24, 30.00, 'ml'),
(1, 25, 10.00, 'gramme'),
(1, 35, 1.00, 'pincée'),
(1, 36, 1.00, 'pincée'),
(3, 19, 200.00, 'gramme'),
(3, 25, 30.00, 'gramme'),
(3, 28, 50.00, 'gramme'),
(3, 35, 1.00, 'pincée'),
(3, 36, 1.00, 'pincée');

-- --------------------------------------------------------

--
-- Structure de la table `recette_ustensile`
--

CREATE TABLE `recette_ustensile` (
  `ID_RECETTE` int(11) NOT NULL,
  `ID_USTENSILE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `recette_ustensile`
--

INSERT INTO `recette_ustensile` (`ID_RECETTE`, `ID_USTENSILE`) VALUES
(1, 3),
(1, 7),
(2, 5),
(3, 4),
(4, 5),
(5, 4),
(6, 2),
(7, 2),
(8, 4),
(9, 2),
(10, 4);

-- --------------------------------------------------------

--
-- Structure de la table `ustensile`
--

CREATE TABLE `ustensile` (
  `ID_USTENSILE` int(11) NOT NULL,
  `NOM` varchar(100) NOT NULL,
  `DESCRIPTION` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ustensile`
--

INSERT INTO `ustensile` (`ID_USTENSILE`, `NOM`, `DESCRIPTION`) VALUES
(1, 'Plaque de cuisson', 'Pour faire chauffer et cuire les aliments'),
(2, 'Four', 'Pour cuire, rôtir et gratiner'),
(3, 'Poêle', 'Pour faire sauter et frire'),
(4, 'Casserole', 'Pour faire bouillir et mijoter'),
(5, 'Couteau', 'Pour découper et hacher'),
(6, 'Mixeur', 'Pour mixer et broyer'),
(7, 'Fouet', 'Pour battre et mélanger'),
(8, 'Barbecue', 'Pour griller en extérieur');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ID_UTILISATEUR` int(11) NOT NULL,
  `NOM` varchar(100) NOT NULL,
  `EMAIL` varchar(150) NOT NULL,
  `MOT_DE_PASSE` varchar(255) NOT NULL,
  `ROLE` varchar(50) DEFAULT 'utilisateur',
  `DATE_CREATION` timestamp NOT NULL DEFAULT current_timestamp(),
  `DATE_ABON` timestamp NULL DEFAULT NULL,
  `ID_ABONNEMENT` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID_UTILISATEUR`, `NOM`, `EMAIL`, `MOT_DE_PASSE`, `ROLE`, `DATE_CREATION`, `DATE_ABON`, `ID_ABONNEMENT`) VALUES
(1, 'Admin', 'admin@cookbot.com', 'admin123', 'administrateur', '2025-06-05 12:37:09', NULL, 3),
(2, 'Chef', 'chef@cookbot.com', 'chef123', 'chef', '2025-06-05 12:37:09', NULL, 2),
(3, 'Utilisateur', 'user@cookbot.com', 'user123', 'utilisateur', '2025-06-05 12:37:09', NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`ID_ABONNEMENT`);

--
-- Index pour la table `favori`
--
ALTER TABLE `favori`
  ADD PRIMARY KEY (`ID_RECETTE`,`ID_UTILISATEUR`),
  ADD KEY `ID_UTILISATEUR` (`ID_UTILISATEUR`);

--
-- Index pour la table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`ID_INGREDIENT`);

--
-- Index pour la table `recette`
--
ALTER TABLE `recette`
  ADD PRIMARY KEY (`ID_RECETTE`),
  ADD KEY `ID_UTILISATEUR` (`ID_UTILISATEUR`);

--
-- Index pour la table `recette_ingredient`
--
ALTER TABLE `recette_ingredient`
  ADD PRIMARY KEY (`ID_RECETTE`,`ID_INGREDIENT`),
  ADD KEY `ID_INGREDIENT` (`ID_INGREDIENT`);

--
-- Index pour la table `recette_ustensile`
--
ALTER TABLE `recette_ustensile`
  ADD PRIMARY KEY (`ID_RECETTE`,`ID_USTENSILE`),
  ADD KEY `ID_USTENSILE` (`ID_USTENSILE`);

--
-- Index pour la table `ustensile`
--
ALTER TABLE `ustensile`
  ADD PRIMARY KEY (`ID_USTENSILE`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ID_UTILISATEUR`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`),
  ADD KEY `ID_ABONNEMENT` (`ID_ABONNEMENT`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `ID_ABONNEMENT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `ID_INGREDIENT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `recette`
--
ALTER TABLE `recette`
  MODIFY `ID_RECETTE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `ustensile`
--
ALTER TABLE `ustensile`
  MODIFY `ID_USTENSILE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ID_UTILISATEUR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `favori`
--
ALTER TABLE `favori`
  ADD CONSTRAINT `favori_ibfk_1` FOREIGN KEY (`ID_RECETTE`) REFERENCES `recette` (`ID_RECETTE`),
  ADD CONSTRAINT `favori_ibfk_2` FOREIGN KEY (`ID_UTILISATEUR`) REFERENCES `utilisateur` (`ID_UTILISATEUR`);

--
-- Contraintes pour la table `recette`
--
ALTER TABLE `recette`
  ADD CONSTRAINT `recette_ibfk_1` FOREIGN KEY (`ID_UTILISATEUR`) REFERENCES `utilisateur` (`ID_UTILISATEUR`);

--
-- Contraintes pour la table `recette_ingredient`
--
ALTER TABLE `recette_ingredient`
  ADD CONSTRAINT `recette_ingredient_ibfk_1` FOREIGN KEY (`ID_RECETTE`) REFERENCES `recette` (`ID_RECETTE`),
  ADD CONSTRAINT `recette_ingredient_ibfk_2` FOREIGN KEY (`ID_INGREDIENT`) REFERENCES `ingredient` (`ID_INGREDIENT`);

--
-- Contraintes pour la table `recette_ustensile`
--
ALTER TABLE `recette_ustensile`
  ADD CONSTRAINT `recette_ustensile_ibfk_1` FOREIGN KEY (`ID_RECETTE`) REFERENCES `recette` (`ID_RECETTE`),
  ADD CONSTRAINT `recette_ustensile_ibfk_2` FOREIGN KEY (`ID_USTENSILE`) REFERENCES `ustensile` (`ID_USTENSILE`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`ID_ABONNEMENT`) REFERENCES `abonnement` (`ID_ABONNEMENT`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
