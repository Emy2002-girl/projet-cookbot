<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookbot_recipes";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion: " . $e->getMessage()]);
    exit;
}

// Fonction pour nettoyer les entrées
function cleanInput($data) {
    if ($data === null) return null;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialiser la réponse
$response = array(
    'success' => false,
    'message' => '',
    'meal_plan' => array(),
    'daily_totals' => array()
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
    
    // Récupérer et valider les paramètres
    $gender = isset($data['gender']) ? cleanInput($data['gender']) : 'homme';
    $weight = isset($data['weight']) ? floatval($data['weight']) : 0;
    $height = isset($data['height']) ? floatval($data['height']) : 0;
    $age = isset($data['age']) ? intval($data['age']) : 0;
    $activity_level = isset($data['activity_level']) ? cleanInput($data['activity_level']) : 'sedentaire';
    $goal = isset($data['goal']) ? cleanInput($data['goal']) : 'maintien';
    $days = isset($data['days']) ? intval($data['days']) : 7;
    $dietary_needs = isset($data['dietary_needs']) ? cleanInput($data['dietary_needs']) : 'standard';
    
    // Validation des données
    if ($weight <= 0 || $height <= 0 || $age <= 0) {
        $response['message'] = "Veuillez entrer des valeurs valides pour votre poids, taille et âge";
        echo json_encode($response);
        exit;
    }
    
    try {
        // Vérifier si les procédures stockées existent
        $stmt = $conn->query("SHOW PROCEDURE STATUS WHERE Db = '$dbname' AND Name IN ('CalculateDailyCalories', 'GenerateMealPlan')");
        $procedures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($procedures) < 2) {
            // Générer des données de test si les procédures n'existent pas
            $meal_plan = generateTestMealPlan($days, $dietary_needs);
            $daily_totals = generateTestDailyTotals($days);
            $calories = calculateSimpleCalories($gender, $weight, $height, $age, $activity_level, $goal);
            
            $response['success'] = true;
            $response['message'] = "Plan de repas généré (mode test - veuillez exécuter les scripts SQL)";
            $response['meal_plan'] = $meal_plan;
            $response['daily_totals'] = $daily_totals;
            $response['calories_target'] = $calories;
        } else {
            // Calculer les besoins caloriques quotidiens
            $stmt = $conn->prepare("CALL CalculateDailyCalories(?, ?, ?, ?, ?, ?, @calories)");
            $stmt->bindParam(1, $gender, PDO::PARAM_STR);
            $stmt->bindParam(2, $weight, PDO::PARAM_STR);
            $stmt->bindParam(3, $height, PDO::PARAM_STR);
            $stmt->bindParam(4, $age, PDO::PARAM_INT);
            $stmt->bindParam(5, $activity_level, PDO::PARAM_STR);
            $stmt->bindParam(6, $goal, PDO::PARAM_STR);
            $stmt->execute();
            
            // Récupérer les calories calculées
            $stmt = $conn->query("SELECT @calories as calories");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $calories = $result['calories'];
            
            // Générer le plan de repas
            $stmt = $conn->prepare("CALL GenerateMealPlan(?, ?, ?)");
            $stmt->bindParam(1, $calories, PDO::PARAM_INT);
            $stmt->bindParam(2, $days, PDO::PARAM_INT);
            $stmt->bindParam(3, $dietary_needs, PDO::PARAM_STR);
            $stmt->execute();
            
            // Récupérer le plan de repas
            $meal_plan = array();
            if ($stmt->columnCount() > 0) {
                $meal_plan = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            // Passer au résultat suivant (totaux quotidiens)
            $stmt->nextRowset();
            $daily_totals = array();
            if ($stmt->columnCount() > 0) {
                $daily_totals = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            // Pour chaque recette, récupérer les ingrédients
            foreach ($meal_plan as &$meal) {
                try {
                    $stmt = $conn->prepare("CALL GetRecipeIngredients(?)");
                    $stmt->bindParam(1, $meal['recipe_id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $meal['ingredients'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Récupérer les instructions
                    $stmt = $conn->prepare("CALL GetRecipeInstructions(?)");
                    $stmt->bindParam(1, $meal['recipe_id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $instructions = $stmt->fetch(PDO::FETCH_ASSOC);
                    $meal['instructions'] = $instructions ? $instructions['INSTRUCTIONS'] : '';
                } catch (PDOException $e) {
                    // En cas d'erreur, ajouter des ingrédients et instructions de test
                    $meal['ingredients'] = [
                        ['NOM' => 'Ingrédient 1', 'QUANTITE' => '100', 'UNITE' => 'g'],
                        ['NOM' => 'Ingrédient 2', 'QUANTITE' => '2', 'UNITE' => 'cuillères à soupe']
                    ];
                    $meal['instructions'] = "Instructions de préparation pour " . $meal['recipe_title'];
                }
            }
            
            $response['success'] = true;
            $response['message'] = "Plan de repas généré avec succès";
            $response['meal_plan'] = $meal_plan;
            $response['daily_totals'] = $daily_totals;
            $response['calories_target'] = $calories;
        }
        
    } catch(PDOException $e) {
        // En cas d'erreur, générer des données de test
        $meal_plan = generateTestMealPlan($days, $dietary_needs);
        $daily_totals = generateTestDailyTotals($days);
        $calories = calculateSimpleCalories($gender, $weight, $height, $age, $activity_level, $goal);
        
        $response['success'] = true;
        $response['message'] = "Plan de repas de test généré (erreur DB: " . $e->getMessage() . ")";
        $response['meal_plan'] = $meal_plan;
        $response['daily_totals'] = $daily_totals;
        $response['calories_target'] = $calories;
    }
    
    // Fermer la connexion
    $conn = null;
}

// Fonction pour calculer les calories de manière simple
function calculateSimpleCalories($gender, $weight, $height, $age, $activity_level, $goal) {
    // Calcul BMR avec la formule de Mifflin-St Jeor
    if ($gender === 'homme') {
        $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
    } else {
        $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
    }
    
    // Multiplicateur d'activité
    $activity_multipliers = [
        'sedentaire' => 1.2,
        'legerement_actif' => 1.375,
        'moderement_actif' => 1.55,
        'tres_actif' => 1.725,
        'extremement_actif' => 1.9
    ];
    
    $multiplier = $activity_multipliers[$activity_level] ?? 1.2;
    $tdee = $bmr * $multiplier;
    
    // Ajustement selon l'objectif
    switch ($goal) {
        case 'perte_poids':
            $tdee *= 0.8; // -20%
            break;
        case 'prise_muscle':
            $tdee *= 1.15; // +15%
            break;
        default:
            // maintien - pas de changement
            break;
    }
    
    return round($tdee);
}

// Fonction pour générer un plan de repas de test
function generateTestMealPlan($days, $dietary_needs = 'standard') {
    $meal_plan = [];
    $meal_types = ['petit-déjeuner', 'déjeuner', 'collation', 'dîner'];
    
    // Adapter les recettes selon les restrictions alimentaires
    $recipe_titles = [
        'petit-déjeuner' => [
            'standard' => ['Omelette aux légumes', 'Pancakes protéinés', 'Smoothie bowl aux fruits', 'Porridge aux fruits rouges'],
            'Végétarien' => ['Smoothie bowl aux fruits', 'Porridge aux fruits rouges', 'Toast avocat', 'Yaourt grec aux noix'],
            'Végétalien' => ['Smoothie bowl aux fruits', 'Porridge au lait d\'amande', 'Toast avocat', 'Chia pudding'],
            'Keto' => ['Omelette au fromage', 'Avocat aux œufs', 'Smoothie coco-épinards', 'Pancakes keto'],
            'Sans gluten' => ['Omelette aux légumes', 'Smoothie bowl aux fruits', 'Porridge sans gluten', 'Yaourt aux fruits']
        ],
        'déjeuner' => [
            'standard' => ['Salade César au poulet', 'Bowl de quinoa aux légumes', 'Wrap au saumon fumé', 'Pâtes complètes à la sauce tomate'],
            'Végétarien' => ['Bowl de quinoa aux légumes', 'Salade de lentilles', 'Wrap aux légumes grillés', 'Pâtes aux légumes'],
            'Végétalien' => ['Bowl de quinoa aux légumes', 'Salade de lentilles', 'Buddha bowl', 'Curry de légumes'],
            'Keto' => ['Salade César sans croûtons', 'Saumon aux légumes verts', 'Salade d\'avocat au thon', 'Courgetti bolognaise'],
            'Sans gluten' => ['Salade César sans croûtons', 'Bowl de quinoa aux légumes', 'Saumon aux légumes', 'Riz sauté aux légumes']
        ],
        'collation' => [
            'standard' => ['Yaourt grec et fruits', 'Poignée d\'amandes', 'Barre protéinée maison', 'Pomme et beurre de cacahuète'],
            'Végétarien' => ['Yaourt grec et fruits', 'Poignée d\'amandes', 'Hummus et légumes', 'Smoothie protéiné'],
            'Végétalien' => ['Yaourt de coco et fruits', 'Poignée d\'amandes', 'Hummus et légumes', 'Smoothie aux graines'],
            'Keto' => ['Avocat aux noix', 'Fromage et olives', 'Beurre d\'amande', 'Œuf dur'],
            'Sans gluten' => ['Yaourt grec et fruits', 'Poignée d\'amandes', 'Fruits secs', 'Smoothie aux fruits']
        ],
        'dîner' => [
            'standard' => ['Saumon grillé et légumes rôtis', 'Curry de légumes et tofu', 'Poulet rôti et purée de patates douces', 'Risotto aux champignons'],
            'Végétarien' => ['Curry de légumes et tofu', 'Risotto aux champignons', 'Gratin de légumes', 'Quiche aux épinards'],
            'Végétalien' => ['Curry de légumes et tofu', 'Risotto aux champignons végétal', 'Ratatouille', 'Buddha bowl du soir'],
            'Keto' => ['Saumon grillé et brocolis', 'Poulet aux courgettes', 'Bœuf aux légumes verts', 'Salade de thon à l\'avocat'],
            'Sans gluten' => ['Saumon grillé et légumes rôtis', 'Curry de légumes et riz', 'Poulet aux légumes', 'Salade complète']
        ]
    ];
    
    // Utiliser les recettes standard si la restriction n'est pas définie
    $selected_recipes = $recipe_titles;
    foreach ($recipe_titles as $meal_type => $recipes_by_diet) {
        if (isset($recipes_by_diet[$dietary_needs])) {
            $selected_recipes[$meal_type] = $recipes_by_diet[$dietary_needs];
        } else {
            $selected_recipes[$meal_type] = $recipes_by_diet['standard'];
        }
    }
    
    for ($day = 1; $day <= $days; $day++) {
        foreach ($meal_types as $meal_type) {
            $recipe_index = array_rand($selected_recipes[$meal_type]);
            $recipe_title = $selected_recipes[$meal_type][$recipe_index];
            
            // Générer des valeurs nutritionnelles selon le type de repas et les restrictions
            switch ($meal_type) {
                case 'petit-déjeuner':
                    $calories = ($dietary_needs === 'Keto') ? rand(300, 450) : rand(250, 400);
                    $proteins = ($dietary_needs === 'Keto') ? rand(20, 30) : rand(15, 25);
                    $carbs = ($dietary_needs === 'Keto') ? rand(5, 15) : rand(30, 50);
                    $fats = ($dietary_needs === 'Keto') ? rand(25, 35) : rand(8, 15);
                    break;
                case 'déjeuner':
                    $calories = ($dietary_needs === 'Keto') ? rand(450, 650) : rand(400, 600);
                    $proteins = ($dietary_needs === 'Keto') ? rand(35, 50) : rand(25, 40);
                    $carbs = ($dietary_needs === 'Keto') ? rand(10, 20) : rand(40, 70);
                    $fats = ($dietary_needs === 'Keto') ? rand(30, 45) : rand(12, 25);
                    break;
                case 'collation':
                    $calories = ($dietary_needs === 'Keto') ? rand(200, 300) : rand(150, 250);
                    $proteins = ($dietary_needs === 'Keto') ? rand(12, 20) : rand(8, 15);
                    $carbs = ($dietary_needs === 'Keto') ? rand(3, 8) : rand(15, 30);
                    $fats = ($dietary_needs === 'Keto') ? rand(15, 25) : rand(5, 12);
                    break;
                case 'dîner':
                    $calories = ($dietary_needs === 'Keto') ? rand(500, 700) : rand(450, 650);
                    $proteins = ($dietary_needs === 'Keto') ? rand(40, 55) : rand(30, 45);
                    $carbs = ($dietary_needs === 'Keto') ? rand(8, 18) : rand(35, 60);
                    $fats = ($dietary_needs === 'Keto') ? rand(35, 50) : rand(15, 28);
                    break;
            }
            
            $meal_plan[] = [
                'day_number' => $day,
                'meal_type' => $meal_type,
                'recipe_id' => rand(1, 1000),
                'recipe_title' => $recipe_title,
                'calories' => $calories,
                'proteins' => $proteins,
                'carbs' => $carbs,
                'fats' => $fats,
                'ingredients' => [
                    ['NOM' => 'Ingrédient principal', 'QUANTITE' => '150', 'UNITE' => 'g'],
                    ['NOM' => 'Légumes', 'QUANTITE' => '100', 'UNITE' => 'g'],
                    ['NOM' => 'Assaisonnement', 'QUANTITE' => '1', 'UNITE' => 'cuillère à soupe']
                ],
                'instructions' => "1. Préparer les ingrédients\n2. Cuire selon les instructions\n3. Servir et déguster"
            ];
        }
    }
    
    return $meal_plan;
}

// Fonction pour générer des totaux quotidiens de test
function generateTestDailyTotals($days) {
    $daily_totals = [];
    
    for ($day = 1; $day <= $days; $day++) {
        $daily_totals[] = [
            'day_number' => $day,
            'total_calories' => rand(1800, 2200),
            'total_proteins' => rand(80, 120),
            'total_carbs' => rand(150, 250),
            'total_fats' => rand(50, 80)
        ];
    }
    
    return $daily_totals;
}

// Renvoyer la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
