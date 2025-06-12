<?php
// Fichier de traitement pour la fonctionnalité MacrosChef
// Ce fichier reçoit les données du formulaire et renvoie les recettes correspondantes

// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookbot_recipes";

// Fonction pour nettoyer les entrées
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialiser la réponse
$response = array(
    'success' => false,
    'message' => '',
    'recipes' => array()
);

// Vérifier si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données JSON envoyées
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    // Vérifier si les données sont valides
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = "Erreur: Données JSON invalides";
        echo json_encode($response);
        exit;
    }
    
    // Récupérer et valider les macronutriments
    $glucides = isset($data['glucides']) ? intval($data['glucides']) : 0;
    $proteines = isset($data['proteines']) ? intval($data['proteines']) : 0;
    $lipides = isset($data['lipides']) ? intval($data['lipides']) : 0;
    $type_repas = isset($data['type_repas']) ? cleanInput($data['type_repas']) : '';
    $besoin_alimentaire = isset($data['besoin_alimentaire']) ? cleanInput($data['besoin_alimentaire']) : 'standard';
    
    // Validation des données
    if ($glucides <= 0 || $proteines <= 0 || $lipides <= 0) {
        $response['message'] = "Veuillez entrer des valeurs valides pour les macronutriments";
        echo json_encode($response);
        exit;
    }
    
    try {
        // Connexion à la base de données
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Définir la marge de tolérance (20%)
        $tolerance = 0.2;
        $glucides_min = $glucides * (1 - $tolerance);
        $glucides_max = $glucides * (1 + $tolerance);
        $proteines_min = $proteines * (1 - $tolerance);
        $proteines_max = $proteines * (1 + $tolerance);
        $lipides_min = $lipides * (1 - $tolerance);
        $lipides_max = $lipides * (1 + $tolerance);
        
        // Construire la requête SQL de base
        $sql = "SELECT * FROM recette WHERE 
                GLUCIDES BETWEEN :glucides_min AND :glucides_max AND
                PROTEINES BETWEEN :proteines_min AND :proteines_max AND
                LIPIDES BETWEEN :lipides_min AND :lipides_max";
        
        // Ajouter le filtre par type de repas si spécifié
        if (!empty($type_repas)) {
            $sql .= " AND TYPE_REPAS = :type_repas";
        }
        
        // Ajouter les filtres selon les besoins alimentaires
        switch ($besoin_alimentaire) {
            case 'besoin1': // Végétarien
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.CATEGORIE = 'Viande'
                )";
                break;
            case 'besoin2': // Pescétarien
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.CATEGORIE = 'Viande' AND i.NOM NOT LIKE '%poisson%'
                )";
                break;
            case 'besoin3': // Végétalien
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.CATEGORIE IN ('Viande', 'Produit laitier')
                )";
                break;
            case 'besoin4': // Sans produits laitiers
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.CATEGORIE = 'Produit laitier'
                )";
                break;
            case 'besoin5': // Sans gluten
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.NOM IN ('Blé', 'Orge', 'Seigle', 'Avoine', 'Farine de blé')
                )";
                break;
            case 'besoin6': // Cétogène
                $sql .= " AND GLUCIDES < 10 AND LIPIDES > 30";
                break;
            case 'besoin7': // Paléo
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.CATEGORIE IN ('Céréale', 'Légumineuse', 'Produit laitier')
                )";
                break;
        }
        
        // Ajouter le tri et la limite
        $sql .= " ORDER BY ABS(GLUCIDES - :glucides) + ABS(PROTEINES - :proteines) + ABS(LIPIDES - :lipides) ASC LIMIT 5";
        
        // Préparer et exécuter la requête
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':glucides_min', $glucides_min, PDO::PARAM_STR);
        $stmt->bindParam(':glucides_max', $glucides_max, PDO::PARAM_STR);
        $stmt->bindParam(':proteines_min', $proteines_min, PDO::PARAM_STR);
        $stmt->bindParam(':proteines_max', $proteines_max, PDO::PARAM_STR);
        $stmt->bindParam(':lipides_min', $lipides_min, PDO::PARAM_STR);
        $stmt->bindParam(':lipides_max', $lipides_max, PDO::PARAM_STR);
        $stmt->bindParam(':glucides', $glucides, PDO::PARAM_STR);
        $stmt->bindParam(':proteines', $proteines, PDO::PARAM_STR);
        $stmt->bindParam(':lipides', $lipides, PDO::PARAM_STR);
        
        if (!empty($type_repas)) {
            $stmt->bindParam(':type_repas', $type_repas, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Si des recettes ont été trouvées
        if (count($recipes) > 0) {
            // Pour chaque recette, récupérer les ingrédients
            foreach ($recipes as &$recipe) {
                $stmt = $conn->prepare("
                    SELECT i.NOM, ri.QUANTITE, ri.UNITE 
                    FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE ri.ID_RECETTE = :id_recette
                ");
                $stmt->bindParam(':id_recette', $recipe['ID_RECETTE'], PDO::PARAM_INT);
                $stmt->execute();
                $recipe['ingredients_details'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Calculer le pourcentage de correspondance
                $glucides_match = 100 - min(100, abs($recipe['GLUCIDES'] - $glucides) / $glucides * 100);
                $proteines_match = 100 - min(100, abs($recipe['PROTEINES'] - $proteines) / $proteines * 100);
                $lipides_match = 100 - min(100, abs($recipe['LIPIDES'] - $lipides) / $lipides * 100);
                $recipe['match_percentage'] = round(($glucides_match + $proteines_match + $lipides_match) / 3);
            }
            
            $response['success'] = true;
            $response['recipes'] = $recipes;
        } else {
            $response['message'] = "Aucune recette ne correspond à vos critères";
        }
        
    } catch(PDOException $e) {
        $response['message'] = "Erreur de base de données: " . $e->getMessage();
    }
    
    // Fermer la connexion
    $conn = null;
}

// Renvoyer la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
