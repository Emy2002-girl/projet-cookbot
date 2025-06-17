<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cookbot_recipes";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Fonction pour calculer les besoins caloriques
function calculateCalories($gender, $weight, $height, $age, $activityLevel, $goal) {
    // Méthode Mifflin-St Jeor
    if ($gender === 'homme') {
        $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
    } else {
        $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
    }

    $activityMultiplier = [
        'sedentaire' => 1.2,
        'léger' => 1.375,
        'modéré' => 1.55,
        'actif' => 1.725,
        'très_actif' => 1.9
    ];

    $dailyCalories = $bmr * ($activityMultiplier[$activityLevel] ?? 1.2);

    // Ajustement selon l'objectif
    switch ($goal) {
        case 'perte_poids':
            $dailyCalories -= 500;
            break;
        case 'prise_muscle':
            $dailyCalories += 500;
            break;
        default:
            break;
    }

    return round($dailyCalories);
}

// Traitement des données
$data = json_decode(file_get_contents('php://input'), true);

$gender = $data['gender'];
$height = $data['height'];
$weight = $data['weight'];
$age = $data['age'];
$goal = $data['goal'];
$activityLevel = $data['activity_level'];
$dietaryNeeds = $data['dietary_needs'];
$days = $data['days'];

if (!$height || !$weight || !$age || !$goal || !$activityLevel) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

// Calcul des calories quotidiennes
$caloriesTarget = calculateCalories($gender, $weight, $height, $age, $activityLevel, $goal);

// Requête SQL pour trouver des recettes adaptées
$sql = "SELECT * FROM recette WHERE ";

switch ($dietaryNeeds) {
    case 'vegetarien':
        $sql .= "ID_RECETTE NOT IN (SELECT ID_RECETTE FROM recette_ingredient JOIN ingredient ON recette_ingredient.ID_INGREDIENT = ingredient.ID_INGREDIENT WHERE CATEGORIE = 'Protéine')";
        break;
    case 'pescetarien':
        $sql .= "ID_RECETTE NOT IN (SELECT ID_RECETTE FROM recette_ingredient JOIN ingredient ON recette_ingredient.ID_INGREDIENT = ingredient.ID_INGREDIENT WHERE CATEGORIE = 'Protéine' AND NOM NOT LIKE '%poisson%')";
        break;
    case 'vegetalien':
        $sql .= "ID_RECETTE NOT IN (SELECT ID_RECETTE FROM recette_ingredient JOIN ingredient ON recette_ingredient.ID_INGREDIENT = ingredient.ID_INGREDIENT WHERE CATEGORIE IN ('Protéine', 'Produit laitier'))";
        break;
    case 'sans_gluten':
        $sql .= "ID_RECETTE NOT IN (SELECT ID_RECETTE FROM recette_ingredient JOIN ingredient ON recette_ingredient.ID_INGREDIENT = ingredient.ID_INGREDIENT WHERE NOM IN ('Blé', 'Orge', 'Seigle', 'Farine de blé'))";
        break;
    case 'sans_lait':
        $sql .= "ID_RECETTE NOT IN (SELECT ID_RECETTE FROM recette_ingredient JOIN ingredient ON recette_ingredient.ID_INGREDIENT = ingredient.ID_INGREDIENT WHERE CATEGORIE = 'Produit laitier')";
        break;
    case 'cetogene':
        $sql .= "GLUCIDES < 10 AND LIPIDES > 30";
        break;
    case 'paleo':
        $sql .= "ID_RECETTE NOT IN (SELECT ID_RECETTE FROM recette_ingredient JOIN ingredient ON recette_ingredient.ID_INGREDIENT = ingredient.ID_INGREDIENT WHERE CATEGORIE IN ('Céréale', 'Légumineuse', 'Produit laitier'))";
        break;
    default:
        break;
}

$sql .= " ORDER BY RAND() LIMIT " . ($days * 3); // 3 repas par jour

$result = $conn->query($sql);

$recipes = [];
while ($row = $result->fetch_assoc()) {
    $recipes[] = $row;
}

$conn->close();

// Organiser par jour
$meal_plan = [];
$daily_totals = [];

for ($i = 0; $i < count($recipes); $i++) {
    $day = floor($i / 3) + 1;
    $recipe = $recipes[$i];
    $recipe['recipe_title'] = $recipe['TITRE'];
    $recipe['calories'] = $recipe['CALORIES'];
    $recipe['proteins'] = $recipe['PROTEINES'];
    $recipe['carbs'] = $recipe['GLUCIDES'];
    $recipe['fats'] = $recipe['LIPIDES'];
    $recipe['meal_type'] = match ($i % 3) {
        0 => 'petit-déjeuner',
        1 => 'déjeuner',
        2 => 'dîner',
    };

    $meal_plan[] = $recipe;

    // Calcul quotidien
    if (!isset($daily_totals[$day - 1])) {
        $daily_totals[$day - 1] = ['day_number' => $day, 'total_calories' => 0, 'total_proteins' => 0, 'total_carbs' => 0, 'total_fats' => 0];
    }
    $daily_totals[$day - 1]['total_calories'] += $recipe['calories'];
    $daily_totals[$day - 1]['total_proteins'] += $recipe['proteins'];
    $daily_totals[$day - 1]['total_carbs'] += $recipe['carbs'];
    $daily_totals[$day - 1]['total_fats'] += $recipe['fats'];
}

echo json_encode([
    'success' => true,
    'meal_plan' => $meal_plan,
    'daily_totals' => array_values($daily_totals),
    'calories_target' => $caloriesTarget
]);
?>