<?php
// Enable CORS for cross-origin requests (for development purposes)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookbot_recipes";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $response = array('success' => false, 'message' => 'Erreur de base de données: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Function to sanitize user inputs
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to calculate the match score between a recipe and target macros
function calculateMatchScore($recipe, $target_glucides, $target_proteines, $target_lipides) {
    // Convertir en float pour éviter les erreurs de type
    $recipe_glucides = floatval($recipe['GLUCIDES']);
    $recipe_proteines = floatval($recipe['PROTEINES']);
    $recipe_lipides = floatval($recipe['LIPIDES']);
    
    $target_glucides = floatval($target_glucides);
    $target_proteines = floatval($target_proteines);
    $target_lipides = floatval($target_lipides);
    
    // Calculer les différences absolues
    $glucides_diff = abs($recipe_glucides - $target_glucides);
    $proteines_diff = abs($recipe_proteines - $target_proteines);
    $lipides_diff = abs($recipe_lipides - $target_lipides);
    
    // Calculer le pourcentage d'écart pour chaque macro (avec protection division par zéro)
    $glucides_error = $target_glucides > 0 ? ($glucides_diff / $target_glucides) * 100 : 0;
    $proteines_error = $target_proteines > 0 ? ($proteines_diff / $target_proteines) * 100 : 0;
    $lipides_error = $target_lipides > 0 ? ($lipides_diff / $target_lipides) * 100 : 0;
    
    // Score global (plus c'est bas, mieux c'est)
    $total_error = ($glucides_error + $proteines_error + $lipides_error) / 3;
    
    // Convertir en pourcentage de correspondance (plus c'est haut, mieux c'est)
    $match_percentage = max(0, 100 - $total_error);
    
    return [
        'score' => $total_error,
        'match_percentage' => round($match_percentage, 1),
        'glucides_match' => max(0, round(100 - $glucides_error, 1)),
        'proteines_match' => max(0, round(100 - $proteines_error, 1)),
        'lipides_match' => max(0, round(100 - $lipides_error, 1))
    ];
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Read the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Initialize the response array
    $response = array('success' => false, 'message' => 'Invalid request.');

    if ($data) {
        // Récupérer et valider les macronutriments
        $glucides = isset($data['glucides']) ? floatval($data['glucides']) : 0;
        $proteines = isset($data['proteines']) ? floatval($data['proteines']) : 0;
        $lipides = isset($data['lipides']) ? floatval($data['lipides']) : 0;
        $type_repas = isset($data['type_repas']) ? cleanInput($data['type_repas']) : '';
        $besoin_alimentaire = isset($data['besoin_alimentaire']) ? cleanInput($data['besoin_alimentaire']) : 'standard';

        // Construire la requête SQL de base - recherche plus large
        $sql = "SELECT * FROM recette WHERE GLUCIDES IS NOT NULL AND PROTEINES IS NOT NULL AND LIPIDES IS NOT NULL";
        $params = array();

        // Ajouter le filtre par type de repas si spécifié
        if (!empty($type_repas)) {
            $sql .= " AND TYPE_REPAS = :type_repas";
            $params[':type_repas'] = $type_repas;
        }

        // Limiter à un nombre raisonnable de recettes pour le traitement
        $sql .= " LIMIT 100";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate match scores for each recipe
            $scored_recipes = array();
            foreach ($recipes as $recipe) {
                $score_data = calculateMatchScore($recipe, $glucides, $proteines, $lipides);
                $recipe['match_score'] = $score_data['score'];
                $recipe['match_percentage'] = $score_data['match_percentage'];
                $recipe['glucides_match'] = $score_data['glucides_match'];
                $recipe['proteines_match'] = $score_data['proteines_match'];
                $recipe['lipides_match'] = $score_data['lipides_match'];
                $scored_recipes[] = $recipe;
            }

            // Sort recipes by match score (ascending, lower is better)
            usort($scored_recipes, function($a, $b) {
                return $a['match_score'] <=> $b['match_score'];
            });

            // Take the top 3 recipes
            $best_recipes = array_slice($scored_recipes, 0, 3);

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
                $response['message'] = count($best_recipes) . " recette(s) trouvée(s) avec " . round($best_recipes[0]['match_percentage'], 1) . "% de correspondance pour la meilleure";
            } else {
                // Si aucune recette après filtrage, essayer sans restrictions alimentaires
                $sql_fallback = "SELECT * FROM recette WHERE GLUCIDES IS NOT NULL AND PROTEINES IS NOT NULL AND LIPIDES IS NOT NULL";
                if (!empty($type_repas)) {
                    $sql_fallback .= " AND TYPE_REPAS = :type_repas";
                }
                $sql_fallback .= " LIMIT 50";
                
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

        } catch (PDOException $e) {
            // Log the error to a file or error reporting system
            error_log("Database query failed: " . $e->getMessage());
            $response['success'] = false;
            $response['message'] = "Database query failed: " . $e->getMessage();
        }
    }

    // Set the content type to JSON
    header('Content-Type: application/json');

    // Encode the response array as JSON and echo it
    echo json_encode($response);
} else {
    // Handle non-POST requests
    $response = array('success' => false, 'message' => 'Invalid request method.');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
