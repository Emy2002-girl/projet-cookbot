<?php
// Fichier de traitement amélioré pour la fonctionnalité MacrosChef
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

// Fonction pour calculer le score de correspondance
function calculateMatchScore($recipe, $target_glucides, $target_proteines, $target_lipides) {
    $glucides_diff = abs($recipe['GLUCIDES'] - $target_glucides);
    $proteines_diff = abs($recipe['PROTEINES'] - $target_proteines);
    $lipides_diff = abs($recipe['LIPIDES'] - $target_lipides);
    
    // Calculer le pourcentage d'écart pour chaque macro
    $glucides_error = $target_glucides > 0 ? ($glucides_diff / $target_glucides) * 100 : 0;
    $proteines_error = $target_proteines > 0 ? ($proteines_diff / $target_proteines) * 100 : 0;
    $lipides_error = $target_lipides > 0 ? ($lipides_diff / $target_lipides) * 100 : 0;
    
    // Score global (plus c'est bas, mieux c'est)
    $total_error = ($glucides_error + $proteines_error + $lipides_error) / 3;
    
    // Convertir en pourcentage de correspondance (plus c'est haut, mieux c'est)
    $match_percentage = max(0, 100 - $total_error);
    
    return [
        'score' => $total_error,
        'match_percentage' => round($match_percentage),
        'glucides_match' => max(0, 100 - $glucides_error),
        'proteines_match' => max(0, 100 - $proteines_error),
        'lipides_match' => max(0, 100 - $lipides_error)
    ];
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
        
        // Construire la requête SQL de base - recherche plus large
        $sql = "SELECT * FROM recette WHERE 1=1";
        $params = array();
        
        // Ajouter le filtre par type de repas si spécifié
        if (!empty($type_repas)) {
            $sql .= " AND TYPE_REPAS = :type_repas";
            $params[':type_repas'] = $type_repas;
        }
        
        // Ajouter les filtres selon les besoins alimentaires
        switch ($besoin_alimentaire) {
            case 'besoin1': // Végétarien
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT DISTINCT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.NOM IN ('Poulet', 'Bœuf haché', 'Viande', 'Porc', 'Agneau')
                )";
                break;
            case 'besoin2': // Pescétarien
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT DISTINCT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.NOM IN ('Poulet', 'Bœuf haché', 'Viande', 'Porc', 'Agneau')
                )";
                break;
            case 'besoin3': // Végétalien
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT DISTINCT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.NOM IN ('Poulet', 'Bœuf haché', 'Viande', 'Porc', 'Agneau', 'Œuf', 'Lait', 'Beurre', 'Fromage râpé', 'Yaourt nature', 'Mozzarella', 'Parmesan')
                )";
                break;
            case 'besoin4': // Sans produits laitiers
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT DISTINCT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.NOM IN ('Lait', 'Beurre', 'Fromage râpé', 'Yaourt nature', 'Mozzarella', 'Parmesan', 'Cheese')
                )";
                break;
            case 'besoin5': // Sans gluten
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT DISTINCT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.NOM IN ('Farine', 'Pain', 'Pâtes')
                )";
                break;
            case 'besoin6': // Cétogène (très peu de glucides, beaucoup de lipides)
                $sql .= " AND GLUCIDES <= 20 AND LIPIDES >= 15";
                break;
            case 'besoin7': // Paléo
                $sql .= " AND ID_RECETTE NOT IN (
                    SELECT DISTINCT ri.ID_RECETTE FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE i.NOM IN ('Riz', 'Pâtes', 'Farine', 'Pain', 'Quinoa', 'Lentilles', 'Lait', 'Beurre', 'Fromage râpé', 'Yaourt nature')
                )";
                break;
        }
        
        // Limiter à un nombre raisonnable de recettes pour le traitement
        $sql .= " LIMIT 50";
        
        // Préparer et exécuter la requête
        $stmt = $conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        $all_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculer les scores pour toutes les recettes
        $scored_recipes = array();
        foreach ($all_recipes as $recipe) {
            $score_data = calculateMatchScore($recipe, $glucides, $proteines, $lipides);
            $recipe['match_score'] = $score_data['score'];
            $recipe['match_percentage'] = $score_data['match_percentage'];
            $recipe['glucides_match'] = $score_data['glucides_match'];
            $recipe['proteines_match'] = $score_data['proteines_match'];
            $recipe['lipides_match'] = $score_data['lipides_match'];
            
            $scored_recipes[] = $recipe;
        }
        
        // Trier par score (meilleur score = plus petit nombre)
        usort($scored_recipes, function($a, $b) {
            return $a['match_score'] <=> $b['match_score'];
        });
        
        // Prendre les 5 meilleures recettes
        $best_recipes = array_slice($scored_recipes, 0, 5);
        
        // Si des recettes ont été trouvées
        if (count($best_recipes) > 0) {
            // Pour chaque recette, récupérer les ingrédients
            foreach ($best_recipes as &$recipe) {
                $stmt = $conn->prepare("
                    SELECT i.NOM, ri.QUANTITE, ri.UNITE 
                    FROM recette_ingredient ri
                    JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                    WHERE ri.ID_RECETTE = :id_recette
                ");
                $stmt->bindParam(':id_recette', $recipe['ID_RECETTE'], PDO::PARAM_INT);
                $stmt->execute();
                $recipe['ingredients_details'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            $response['success'] = true;
            $response['recipes'] = $best_recipes;
            $response['message'] = "Recettes trouvées avec correspondance de " . $best_recipes[0]['match_percentage'] . "% pour la meilleure";
        } else {
            // Essayer une recherche encore plus large sans filtres alimentaires
            $sql_fallback = "SELECT * FROM recette WHERE 1=1";
            if (!empty($type_repas)) {
                $sql_fallback .= " AND TYPE_REPAS = :type_repas";
            }
            $sql_fallback .= " LIMIT 20";
            
            $stmt = $conn->prepare($sql_fallback);
            if (!empty($type_repas)) {
                $stmt->bindParam(':type_repas', $type_repas);
            }
            $stmt->execute();
            $fallback_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($fallback_recipes) > 0) {
                // Calculer les scores pour les recettes de fallback
                $scored_fallback = array();
                foreach ($fallback_recipes as $recipe) {
                    $score_data = calculateMatchScore($recipe, $glucides, $proteines, $lipides);
                    $recipe['match_score'] = $score_data['score'];
                    $recipe['match_percentage'] = $score_data['match_percentage'];
                    $scored_fallback[] = $recipe;
                }
                
                // Trier et prendre les 3 meilleures
                usort($scored_fallback, function($a, $b) {
                    return $a['match_score'] <=> $b['match_score'];
                });
                
                $best_fallback = array_slice($scored_fallback, 0, 3);
                
                // Récupérer les ingrédients
                foreach ($best_fallback as &$recipe) {
                    $stmt = $conn->prepare("
                        SELECT i.NOM, ri.QUANTITE, ri.UNITE 
                        FROM recette_ingredient ri
                        JOIN ingredient i ON ri.ID_INGREDIENT = i.ID_INGREDIENT
                        WHERE ri.ID_RECETTE = :id_recette
                    ");
                    $stmt->bindParam(':id_recette', $recipe['ID_RECETTE'], PDO::PARAM_INT);
                    $stmt->execute();
                    $recipe['ingredients_details'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                
                $response['success'] = true;
                $response['recipes'] = $best_fallback;
                $response['message'] = "Recettes alternatives trouvées (restrictions alimentaires ignorées)";
            } else {
                $response['message'] = "Aucune recette disponible dans la base de données";
            }
        }
        
    } catch(PDOException $e) {
        $response['message'] = "Erreur de base de données: " . $e->getMessage();
    }
    
    // Fermer la connexion
    $conn = null;
} else {
    $response['message'] = "Méthode de requête non autorisée";
}

// Renvoyer la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>