<?php

header("Content-Type: application/json");

$db_file = __DIR__ . '/app.db';

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$demande = $input['demande'] ?? '';
$portions = $input['portions'] ?? 1;
$duree = $input['duree'] ?? null;
$niveau = $input['niveau'] ?? null;
$regime = $input['regime'] ?? null;

// Fonction pour filtrer les recettes par régime alimentaire
function filterByDietaryNeeds($recipe, $regime) {
    if ($regime === 'vegetarien') {
        return !preg_match('/(viande|poulet|boeuf|porc|agneau|poisson|fruits de mer)/i', $recipe['ingredients']);
    } elseif ($regime === 'vegetalien') {
        return !preg_match('/(viande|poulet|boeuf|porc|agneau|poisson|fruits de mer|lait|fromage|oeuf|miel)/i', $recipe['ingredients']);
    } elseif ($regime === 'sans_gluten') {
        return !preg_match('/(blé|seigle|orge|farine)/i', $recipe['ingredients']);
    } elseif ($regime === 'sans_produits_laitiers') {
        return !preg_match('/(lait|fromage|beurre|crème)/i', $recipe['ingredients']);
    } elseif ($regime === 'cetogene') {
        // Simplifié pour l'exemple, une vraie implémentation serait plus complexe
        return !preg_match('/(sucre|riz|pâtes|pain|pomme de terre)/i', $recipe['ingredients']);
    } elseif ($regime === 'paleo') {
        // Simplifié pour l'exemple
        return !preg_match('/(produits laitiers|légumineuses|céréales|sucre raffiné|huiles végétales raffinées)/i', $recipe['ingredients']);
    } elseif ($regime === 'pescetarien') {
        return !preg_match('/(viande|poulet|boeuf|porc|agneau)/i', $recipe['ingredients']);
    }
    return true;
}

// Logique de recherche de recettes
$query = "SELECT * FROM recette WHERE 1=1";
$params = [];

if (!empty($demande)) {
    $keywords = explode(' ', $demande);
    $keyword_conditions = [];
    foreach ($keywords as $keyword) {
        $keyword_conditions[] = "(titre LIKE ? OR description LIKE ? OR ingredients LIKE ?)";
        $params[] = '%' . $keyword . '%';
        $params[] = '%' . $keyword . '%';
        $params[] = '%' . $keyword . '%';
    }
    $query .= " AND (" . implode(' OR ', $keyword_conditions) . ")";
}

if (!empty($niveau) && $niveau !== 'standard') {
    $query .= " AND difficulte = ?";
    $params[] = $niveau;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filtered_recipes = [];
foreach ($recipes as $recipe) {
    // Filtrer par durée
    $total_time = $recipe['temps_preparation'] + $recipe['temps_cuisson'];
    if ($duree !== null) {
        if ($duree == 15 && $total_time > 15) continue;
        if ($duree == 30 && ($total_time < 15 || $total_time > 30)) continue;
        if ($duree == 45 && ($total_time < 30 || $total_time > 45)) continue;
        if ($duree == 60 && ($total_time < 45 || $total_time > 60)) continue;
        if ($duree == 90 && ($total_time < 60 || $total_time > 90)) continue;
        if ($duree == 120 && $total_time < 90) continue; // Plus de 1h30
    }

    // Filtrer par régime alimentaire
    if (!filterByDietaryNeeds($recipe, $regime)) {
        continue;
    }

    // Ajuster les portions
    $original_portions = $recipe['portions'] ?? 1;
    $portion_factor = $portions / $original_portions;

    $adjusted_ingredients = [];
    foreach (explode('\n', $recipe['ingredients']) as $ingredient_line) {
        // Tente de trouver un nombre au début de la ligne
        if (preg_match('/^(\d+(\.\d+)?)\s*(.*)/', $ingredient_line, $matches)) {
            $amount = (float)$matches[1];
            $unit_and_item = $matches[3];
            $adjusted_amount = round($amount * $portion_factor, 2);
            $adjusted_ingredients[] = $adjusted_amount . ' ' . $unit_and_item;
        } else {
            $adjusted_ingredients[] = $ingredient_line; // Pas de nombre, ajouter tel quel
        }
    }
    $recipe['ingredients'] = implode('\n', $adjusted_ingredients);
    $recipe['portions'] = $portions;

    $filtered_recipes[] = $recipe;
}

if (!empty($filtered_recipes)) {
    $random_recipe = $filtered_recipes[array_rand($filtered_recipes)];
    echo json_encode([
        'recipeName' => $random_recipe['titre'],
        'description' => $random_recipe['description'],
        'ingredients' => explode('\n', $random_recipe['ingredients']),
        'instructions' => explode('\n', $random_recipe['instructions']),
        'prepTime' => $random_recipe['temps_preparation'],
        'cookTime' => $random_recipe['temps_cuisson'],
        'difficulty' => $random_recipe['niveau'],
        'mealType' => $random_recipe['type_plat'],
        'portions' => $random_recipe['portions']
    ]);
} else {
    echo json_encode(['error' => 'Aucune recette trouvée pour les critères spécifiés.']);
}

?>

