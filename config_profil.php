<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'user.php';

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$message_type = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $database = new Database();
        $db = $database->getConnection();
        $utilisateur = new Utilisateur($db);

        // Récupérer les données du formulaire
        $genre = $_POST['genre'] ?? 'homme';
        $age = intval($_POST['age'] ?? 0);
        $taille = intval($_POST['taille'] ?? 0);
        $poids = intval($_POST['poids'] ?? 0);
        $niveau_cuisine = $_POST['niveau_cuisine'] ?? 'debutant';
        $restrictions = $_POST['restrictions'] ?? 'standard';

        // Mettre à jour le profil utilisateur
        $result = $utilisateur->updateProfile($_SESSION['user_id'], [
            'genre' => $genre,
            'age' => $age,
            'taille' => $taille,
            'poids' => $poids,
            'niveau_cuisine' => $niveau_cuisine,
            'restrictions' => $restrictions
        ]);

        if ($result['success']) {
            $_SESSION['config_complete'] = true;
            // Rediriger vers la page d'accueil
            header('Location: index.php?config_complete=1');
            exit();
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    } catch (Exception $e) {
        $message = 'Erreur: ' . $e->getMessage();
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurez votre profil - CookBot</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Configurez votre profil</h1>
            <p>Pour vous offrir des recettes personnalisées, nous avons besoin de quelques informations supplémentaires.</p>
            
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-section">
                    <h2>Informations personnelles</h2>
                    
                    <div class="form-group">
                        <label for="genre">Genre</label>
                        <select id="genre" name="genre">
                            <option value="homme">Homme</option>
                            <option value="femme">Femme</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="age">Âge</label>
                        <input type="number" id="age" name="age" min="1" max="120" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="taille">Taille (cm)</label>
                        <input type="number" id="taille" name="taille" min="50" max="250" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="poids">Poids (kg)</label>
                        <input type="number" id="poids" name="poids" min="20" max="300" required>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2>Préférences culinaires</h2>
                    
                    <div class="form-group">
                        <label for="niveau_cuisine">Niveau de cuisine</label>
                        <select id="niveau_cuisine" name="niveau_cuisine">
                            <option value="debutant">Débutant</option>
                            <option value="intermediaire">Intermédiaire</option>
                            <option value="avance">Expert</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="restrictions">Restrictions alimentaires</label>
                        <select id="restrictions" name="restrictions">
                            <option value="standard">Aucune restriction</option>
                            <option value="vegetarien">Végétarien</option>
                            <option value="vegan">Végétalien</option>
                            <option value="sans_gluten">Sans gluten</option>
                            <option value="sans_lactose">Sans lactose</option>
                            <option value="paleo">Paléo</option>
                            <option value="keto">Keto</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">Terminer la configuration</button>
            </form>
        </div>
    </div>
</body>
</html>