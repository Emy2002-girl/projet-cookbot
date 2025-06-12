<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuration de la base de données
$host = 'localhost';
$dbname = 'cookbot_recipes';
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = '';     // Remplacez par votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données POST
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

// Récupération des données avec le nouveau champ niveau_cuisine
$niveau_cuisine = isset($input['niveau_cuisine']) ? $input['niveau_cuisine'] : '';
$ingredients = isset($input['ingredients']) ? $input['ingredients'] : [];
$ustensiles = isset($input['ustensiles']) ? $input['ustensiles'] : [];
$duree = isset($input['duree']) ? (int)$input['duree'] : 0;
$type_repas = isset($input['type_repas']) ? $input['type_repas'] : '';

// Validation des données
if (!$niveau_cuisine) {
    http_response_code(400);
    echo json_encode(['error' => 'Le niveau de cuisine est requis']);
    exit;
}

if (empty($ingredients)) {
    http_response_code(400);
    echo json_encode(['error' => 'Au moins un ingrédient est requis']);
    exit;
}

try {
    // Construction de la requête SQL avec filtrage par niveau
    $placeholders = str_repeat('?,', count($ingredients) - 1) . '?';
    
    $sql = "SELECT DISTINCT r.*, 
                   GROUP_CONCAT(DISTINCT i.NOM SEPARATOR ', ') as ingredients_disponibles,
                   COUNT(DISTINCT ri.ID_INGREDIENT) as nb_ingredients_matches
            FROM RECETTE r 
            JOIN RECETTE_INGREDIENT ri ON r.ID_RECETTE = ri.ID_RECETTE 
            JOIN INGREDIENT i ON ri.ID_INGREDIENT = i.ID_INGREDIENT 
            WHERE i.NOM IN ($placeholders)";
    
    $params = $ingredients;
    
    // Filtrage par difficulté selon le niveau
    switch($niveau_cuisine) {
        case 'debutant':
            $sql .= " AND r.DIFFICULTE = 'Novice'";
            break;
        case 'intermediaire':
            $sql .= " AND r.DIFFICULTE IN ('Novice', 'Intermédiaire')";
            break;
        case 'avance':
            $sql .= " AND r.DIFFICULTE IN ('Novice', 'Intermédiaire', 'Expert')";
            break;
    }
    
    // Autres filtres
    if ($type_repas) {
        $sql .= " AND r.TYPE_REPAS = ?";
        $params[] = $type_repas;
    }
    
    if ($duree > 0) {
        $sql .= " AND (r.TEMPS_PREPARATION + r.TEMPS_CUISSON) <= ?";
        $params[] = $duree;
    }
    
    // Filtrage par ustensiles si spécifiés
    if (!empty($ustensiles)) {
        $ustensiles_placeholders = str_repeat('?,', count($ustensiles) - 1) . '?';
        $sql .= " AND r.ID_RECETTE IN (
                    SELECT ru.ID_RECETTE 
                    FROM RECETTE_USTENSILE ru 
                    JOIN USTENSILE u ON ru.ID_USTENSILE = u.ID_USTENSILE 
                    WHERE u.NOM IN ($ustensiles_placeholders)
                    GROUP BY ru.ID_RECETTE 
                    HAVING COUNT(DISTINCT u.ID_USTENSILE) >= 1
                  )";
        $params = array_merge($params, $ustensiles);
    }
    
    $sql .= " GROUP BY r.ID_RECETTE 
              ORDER BY nb_ingredients_matches DESC, r.DIFFICULTE ASC, r.TEMPS_PREPARATION ASC 
              LIMIT 10";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si aucune recette trouvée, proposer des recettes adaptées au niveau
    if (empty($recipes)) {
        $fallback_sql = "SELECT * FROM RECETTE WHERE 1=1";
        $fallback_params = [];
        
        // Filtrer par niveau
        switch($niveau_cuisine) {
            case 'debutant':
                $fallback_sql .= " AND DIFFICULTE = 'Novice'";
                break;
            case 'intermediaire':
                $fallback_sql .= " AND DIFFICULTE IN ('Novice', 'Intermédiaire')";
                break;
            case 'avance':
                $fallback_sql .= " AND DIFFICULTE IN ('Novice', 'Intermédiaire', 'Expert')";
                break;
        }
        
        if ($type_repas) {
            $fallback_sql .= " AND TYPE_REPAS = ?";
            $fallback_params[] = $type_repas;
        }
        
        if ($duree > 0) {
            $fallback_sql .= " AND (TEMPS_PREPARATION + TEMPS_CUISSON) <= ?";
            $fallback_params[] = $duree;
        }
        
        $fallback_sql .= " ORDER BY TEMPS_PREPARATION ASC LIMIT 5";
        
        $fallback_stmt = $pdo->prepare($fallback_sql);
        $fallback_stmt->execute($fallback_params);
        $recipes = $fallback_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Ajouter des informations sur le niveau de correspondance
    foreach ($recipes as &$recipe) {
        $recipe['niveau_utilisateur'] = $niveau_cuisine;
        $recipe['adapte_niveau'] = true;
        
        // Vérifier si la recette est vraiment adaptée au niveau
        switch($niveau_cuisine) {
            case 'debutant':
                $recipe['adapte_niveau'] = ($recipe['DIFFICULTE'] === 'Novice');
                break;
            case 'intermediaire':
                $recipe['adapte_niveau'] = in_array($recipe['DIFFICULTE'], ['Novice', 'Intermédiaire']);
                break;
            case 'avance':
                $recipe['adapte_niveau'] = true; // Tous les niveaux conviennent
                break;
        }
        
        // Ajouter des conseils selon le niveau
        if ($niveau_cuisine === 'debutant' && $recipe['DIFFICULTE'] !== 'Novice') {
            $recipe['conseil_niveau'] = "Cette recette pourrait être un peu complexe pour un débutant. Prenez votre temps !";
        } elseif ($niveau_cuisine === 'avance' && $recipe['DIFFICULTE'] === 'Novice') {
            $recipe['conseil_niveau'] = "Recette simple que vous pouvez personnaliser selon vos goûts !";
        }
    }
    
    // Log pour debug (optionnel)
    error_log("Recherche recettes - Niveau: $niveau_cuisine, Ingrédients: " . implode(',', $ingredients) . ", Résultats: " . count($recipes));
    
    echo json_encode([
        'recipes' => $recipes,
        'niveau_cuisine' => $niveau_cuisine,
        'total_found' => count($recipes),
        'search_criteria' => [
            'ingredients' => $ingredients,
            'ustensiles' => $ustensiles,
            'duree' => $duree,
            'type_repas' => $type_repas,
            'niveau_cuisine' => $niveau_cuisine
        ]
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la recherche: ' . $e->getMessage()]);
}
?>