<?php
session_start();
$user_logged_in = isset($_SESSION['user_id']);
$user_email = $user_logged_in ? ($_SESSION['user_email'] ?? '') : '';
$user_name = $user_logged_in ? ($_SESSION['user_name'] ?? '') : '';
$user_prenom = $user_logged_in ? ($_SESSION['user_prenom'] ?? '') : '';
$initial = $user_logged_in ? strtoupper(substr($user_prenom, 0, 1)) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MealPlanChef - Plan Alimentaire Personnalisé</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <!-- Menu utilisateur -->
    <div class="header-right">
        <?php if ($user_logged_in): ?>
        <div class="user-dropdown">
            <div class="user-button" id="userButton">
                <div class="user-avatar"><?php echo $initial; ?></div>
            </div>
        </div>
        <?php else: ?>
        <a href="login.php">Se connecter</a>
        <?php endif; ?>
    </div>
</header>

<main>
    <h1>Votre Plan Alimentaire Personnalisé</h1>
    <form id="mealplan-form">
        <label for="genre-select">Genre :</label>
        <select id="genre-select" name="genre">
            <option value="homme">Homme</option>
            <option value="femme">Femme</option>
        </select><br>

        <label for="hauteur">Taille (cm) :</label>
        <input type="number" id="hauteur" name="hauteur"><br>

        <label for="poids">Poids (kg) :</label>
        <input type="number" id="poids" name="poids"><br>

        <label for="age">Âge :</label>
        <input type="number" id="age" name="age"><br>

        <label for="type-repas-select">Objectif :</label>
        <select id="type-repas-select" name="objectif">
            <option value="maintien">Maintenir mon poids</option>
            <option value="perte_poids">Perdre du poids</option>
            <option value="prise_muscle">Prendre du muscle</option>
        </select><br>

        <label for="niveau-activité-select">Niveau d’activité :</label>
        <select id="niveau-activité-select" name="activite">
            <option value="sedentaire">Sédentaire</option>
            <option value="léger">Léger</option>
            <option value="modéré">Modéré</option>
            <option value="actif">Actif</option>
            <option value="très_actif">Très actif</option>
        </select><br>

        <label for="besoin-alimentaires-select">Régime alimentaire :</label>
        <select id="besoin-alimentaires-select" name="regime">
            <option value="standard">Aucune restriction</option>
            <option value="vegetarien">Végétarien</option>
            <option value="pescetarien">Pescétarien</option>
            <option value="vegetalien">Végétalien</option>
            <option value="sans_gluten">Sans gluten</option>
            <option value="sans_lait">Sans produits laitiers</option>
            <option value="cetogene">Cétogène</option>
            <option value="paleo">Paléo</option>
        </select><br>

        <label for="durre">Durée (jours) :</label>
        <input type="number" id="durre" name="durre" value="7"><br>

        <button type="submit" id="generate-recipes">Générer votre plan</button>
    </form>

    <div id="recipes-results"></div>
</main>

<script src="scripts.js"></script>
</body>
</html>