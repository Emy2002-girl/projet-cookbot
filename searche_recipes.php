<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Accept');

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db_file = __DIR__ . '/app.db';

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la base de données existe et contient des données
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='recette'")->fetchAll();
    
    if (empty($tables)) {
        // Créer la table si elle n'existe pas
        $pdo->exec("CREATE TABLE IF NOT EXISTS recette (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            titre TEXT NOT NULL,
            description TEXT,
            ingredients TEXT,
            instructions TEXT,
            temps_preparation INTEGER DEFAULT 0,
            temps_cuisson INTEGER DEFAULT 0,
            difficulte TEXT DEFAULT 'debutant',
            type_plat TEXT,
            portions INTEGER DEFAULT 2
        )");
        
        // Insérer quelques recettes d'exemple
        $sample_recipes = [
            [
                'titre' => 'Omelette aux fines herbes',
                'description' => 'Une omelette simple et délicieuse aux herbes fraîches',
                'ingredients' => "3 œufs\n2 cuillères à soupe de lait\n1 cuillère à soupe de beurre\nFines herbes fraîches (persil, ciboulette)\nSel et poivre",
                'instructions' => "Battez les œufs avec le lait dans un bol\nAssaisonnez avec sel et poivre\nFaites chauffer le beurre dans une poêle antiadhésive\nVersez les œufs et cuisez 2-3 minutes\nAjoutez les herbes hachées et pliez l'omelette en deux\nServez immédiatement",
                'temps_preparation' => 5,
                'temps_cuisson' => 5,
                'difficulte' => 'debutant',
                'type_plat' => 'petit-déjeuner',
                'portions' => 2
            ],
            [
                'titre' => 'Pâtes carbonara',
                'description' => 'Des pâtes crémeuses à l\'italienne avec lardons et parmesan',
                'ingredients' => "400g de pâtes (spaghetti ou linguine)\n200g de lardons\n3 œufs entiers\n100g de parmesan râpé\nPoivre noir fraîchement moulu\nSel",
                'instructions' => "Faites cuire les pâtes selon les instructions du paquet\nPendant ce temps, faites revenir les lardons dans une poêle\nBattez les œufs avec le parmesan râpé dans un bol\nÉgouttez les pâtes en gardant un peu d'eau de cuisson\nMélangez immédiatement les pâtes chaudes avec le mélange œufs-parmesan\nAjoutez les lardons et un peu d'eau de cuisson si nécessaire\nPoivrez généreusement et servez",
                'temps_preparation' => 10,
                'temps_cuisson' => 15,
                'difficulte' => 'intermediaire',
                'type_plat' => 'déjeuner',
                'portions' => 4
            ],
            [
                'titre' => 'Salade César',
                'description' => 'Salade fraîche avec sauce César maison et croûtons croustillants',
                'ingredients' => "1 salade romaine\n100g de parmesan\n50g de croûtons\n2 filets d\'anchois\n1 jaune d\'œuf\n2 cuillères à soupe d\'huile d\'olive\n1 citron\n1 gousse d\'ail",
                'instructions' => "Lavez et coupez la salade romaine en morceaux\nPréparez la sauce en mélangeant jaune d'œuf, anchois écrasés, ail pressé et jus de citron\nIncorporez l'huile d'olive petit à petit\nMélangez la salade avec la sauce César\nAjoutez le parmesan râpé et les croûtons\nServez immédiatement bien frais",
                'temps_preparation' => 15,
                'temps_cuisson' => 0,
                'difficulte' => 'debutant',
                'type_plat' => 'déjeuner',
                'portions' => 2
            ],
            [
                'titre' => 'Risotto aux champignons',
                'description' => 'Risotto crémeux aux champignons de saison',
                'ingredients' => "300g de riz arborio\n500g de champignons mélangés\n1 oignon\n2 gousses d\'ail\n1 litre de bouillon de légumes\n100ml de vin blanc\n50g de parmesan\n2 cuillères à soupe d\'huile d\'olive\nBeurre\nSel et poivre",
                'instructions' => "Faites chauffer le bouillon et gardez-le au chaud\nÉmincez l'oignon et l'ail, faites-les revenir dans l'huile\nAjoutez le riz et nacrez-le 2 minutes\nVersez le vin blanc et laissez évaporer\nAjoutez le bouillon louche par louche en remuant\nFaites cuire les champignons séparément\nIncorporez les champignons et le parmesan en fin de cuisson\nFinissez avec une noix de beurre",
                'temps_preparation' => 15,
                'temps_cuisson' => 25,
                'difficulte' => 'intermediaire',
                'type_plat' => 'déjeuner',
                'portions' => 4
            ],
            [
                'titre' => 'Tarte aux pommes',
                'description' => 'Tarte aux pommes classique avec pâte brisée maison',
                'ingredients' => "1 pâte brisée\n6 pommes\n3 œufs\n200ml de crème fraîche\n80g de sucre\n1 cuillère à café de cannelle\nBeurre",
                'instructions' => "Préchauffez le four à 180°C\nÉtalez la pâte dans un moule à tarte\nÉpluchez et coupez les pommes en lamelles\nDisposez les pommes sur la pâte\nBattez les œufs avec la crème, le sucre et la cannelle\nVersez ce mélange sur les pommes\nCuisez 35-40 minutes jusqu'à ce que la tarte soit dorée\nLaissez refroidir avant de servir",
                'temps_preparation' => 20,
                'temps_cuisson' => 40,
                'difficulte' => 'intermediaire',
                'type_plat' => 'dessert',
                'portions' => 6
            ]
        ];
        
        $stmt = $pdo->prepare("INSERT INTO recette (titre, description, ingredients, instructions, temps_preparation, temps_cuisson, difficulte, type_plat, portions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($sample_recipes as $recipe) {
            $stmt->execute([
                $recipe['titre'],
                $recipe['description'],
                $recipe['ingredients'],
                $recipe['instructions'],
                $recipe['temps_preparation'],
                $recipe['temps_cuisson'],
                $recipe['difficulte'],
                $recipe['type_plat'],
                $recipe['portions']
            ]);
        }
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit();
}

// Récupérer les données POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        echo json_encode([
            'success' => false,
            'error' => 'Invalid JSON input'
        ]);
        exit();
    }

    $demande = trim($input['demande'] ?? '');
    $portions = (int)($input['portions'] ?? 2);
    $duree = (int)($input['duree'] ?? 0);
    $niveau = trim($input['niveau'] ?? '');
    $regime = trim($input['regime'] ?? '');

    // Log des paramètres pour débogage
    error_log("Paramètres reçus: demande=$demande, portions=$portions, duree=$duree, niveau=$niveau, regime=$regime");

    // Fonction pour filtrer les recettes par régime alimentaire
    function filterByDietaryNeeds($ingredients, $regime) {
        if (empty($regime) || $regime === 'standard') return true;
        
        $ingredients_lower = strtolower($ingredients);
        
        switch ($regime) {
            case 'vegetarien':
                return !preg_match('/(viande|poulet|bœuf|porc|agneau|poisson|fruits de mer|lardons|anchois)/i', $ingredients_lower);
            case 'vegetalien':
                return !preg_match('/(viande|poulet|bœuf|porc|agneau|poisson|fruits de mer|lait|fromage|œuf|œufs|beurre|crème|miel|lardons|parmesan|anchois)/i', $ingredients_lower);
            case 'sans_gluten':
                return !preg_match('/(blé|seigle|orge|farine|pâtes|pain|croûtons)/i', $ingredients_lower);
            case 'sans_produits_laitiers':
                return !preg_match('/(lait|fromage|beurre|crème|parmesan|yaourt)/i', $ingredients_lower);
            case 'cetogene':
                return !preg_match('/(sucre|riz|pâtes|pain|pomme de terre|farine)/i', $ingredients_lower);
            case 'paleo':
                return !preg_match('/(lait|fromage|légumineuses|céréales|sucre|huiles végétales|pâtes|riz)/i', $ingredients_lower);
            case 'pescetarien':
                return !preg_match('/(viande|poulet|bœuf|porc|agneau|lardons)/i', $ingredients_lower);
            default:
                return true;
        }
    }

    // Construction de la requête de recherche
    $query = "SELECT * FROM recette WHERE 1=1";
    $params = [];

    // Recherche par mots-clés
    if (!empty($demande)) {
        $keywords = explode(' ', $demande);
        $keyword_conditions = [];
        foreach ($keywords as $keyword) {
            $keyword = trim($keyword);
            if (!empty($keyword)) {
                $keyword_conditions[] = "(titre LIKE ? OR description LIKE ? OR ingredients LIKE ?)";
                $params[] = '%' . $keyword . '%';
                $params[] = '%' . $keyword . '%';
                $params[] = '%' . $keyword . '%';
            }
        }
        if (!empty($keyword_conditions)) {
            $query .= " AND (" . implode(' OR ', $keyword_conditions) . ")";
        }
    }

    // Filtrage par niveau
    if (!empty($niveau) && $niveau !== 'standard') {
        $query .= " AND difficulte = ?";
        $params[] = $niveau;
    }

    error_log("SQL Query: " . $query);
    error_log("Params: " . json_encode($params));

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("Recettes trouvées avant filtrage: " . count($recipes));

    // Filtrage post-requête
    $filtered_recipes = [];
    foreach ($recipes as $recipe) {
        // Filtrer par durée
        $total_time = ($recipe['temps_preparation'] ?? 0) + ($recipe['temps_cuisson'] ?? 0);
        
        if ($duree > 0) {
            $duration_match = false;
            
            switch ($duree) {
                case 15:
                    $duration_match = ($total_time <= 15);
                    break;
                case 30:
                    $duration_match = ($total_time > 15 && $total_time <= 30);
                    break;
                case 45:
                    $duration_match = ($total_time > 30 && $total_time <= 45);
                    break;
                case 60:
                    $duration_match = ($total_time > 45 && $total_time <= 60);
                    break;
                case 90:
                    $duration_match = ($total_time > 60 && $total_time <= 90);
                    break;
                case 120:
                    $duration_match = ($total_time > 90);
                    break;
                default:
                    $duration_match = true;
            }
            
            if (!$duration_match) {
                continue;
            }
        }

        // Filtrer par régime alimentaire
        if (!filterByDietaryNeeds($recipe['ingredients'] ?? '', $regime)) {
            continue;
        }

        // Ajuster les portions
        $original_portions = $recipe['portions'] ?? 2;
        $ratio = $portions / $original_portions;
        
        $adjusted_ingredients = [];
        $ingredients_lines = explode("\n", $recipe['ingredients'] ?? '');
        
        foreach ($ingredients_lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Chercher un nombre au début de la ligne
            if (preg_match('/^(\d+(?:[.,]\d+)?)\s*(.*)/', $line, $matches)) {
                $amount = floatval(str_replace(',', '.', $matches[1]));
                $rest = trim($matches[2]);
                
                $newAmount = $amount * $ratio;
                
                // Arrondir intelligemment
                if ($newAmount < 1) {
                    $newAmount = round($newAmount, 2);
                } else if ($newAmount < 10) {
                    $newAmount = round($newAmount, 1);
                } else {
                    $newAmount = round($newAmount);
                }
                
                $adjusted_ingredients[] = $newAmount . ' ' . $rest;
            } else {
                $adjusted_ingredients[] = $line;
            }
        }
        
        $recipe['ingredients'] = implode("\n", $adjusted_ingredients);
        $recipe['portions'] = $portions;
        $recipe['originalRecipeId'] = $recipe['id']; // Ajouter l'ID original pour la sauvegarde

        $filtered_recipes[] = $recipe;
    }

    error_log("Recettes après filtrage: " . count($filtered_recipes));

    // Retourner le résultat
    if (!empty($filtered_recipes)) {
        $selected_recipe = $filtered_recipes[array_rand($filtered_recipes)];
        
        $response = [
            'success' => true,
            'recipeName' => $selected_recipe['titre'],
            'description' => $selected_recipe['description'] ?? 'Description non disponible',
            'ingredients' => array_filter(explode("\n", $selected_recipe['ingredients']), function($item) {
                return trim($item) !== '';
            }),
            'instructions' => array_filter(explode("\n", $selected_recipe['instructions'] ?? ''), function($item) {
                return trim($item) !== '';
            }),
            'prepTime' => $selected_recipe['temps_preparation'] ?? 0,
            'cookTime' => $selected_recipe['temps_cuisson'] ?? 0,
            'difficulty' => $selected_recipe['difficulte'] ?? 'Non spécifié',
            'mealType' => $selected_recipe['type_plat'] ?? 'Non spécifié',
            'portions' => $selected_recipe['portions'],
            'originalRecipeId' => $selected_recipe['id'] ?? 0
        ];
        
        error_log("Réponse envoyée: " . json_encode($response));
        echo json_encode($response);
    } else {
        $error_response = [
            'success' => false,
            'error' => 'Aucune recette trouvée pour les critères spécifiés.',
            'suggestion' => 'Essayez avec des critères moins restrictifs ou une durée plus longue.',
            'debug' => [
                'total_recipes_found' => count($recipes),
                'after_filtering' => count($filtered_recipes),
                'search_criteria' => [
                    'demande' => $demande,
                    'portions' => $portions,
                    'duree' => $duree,
                    'niveau' => $niveau,
                    'regime' => $regime
                ]
            ]
        ];
        
        error_log("Aucune recette trouvée: " . json_encode($error_response));
        echo json_encode($error_response);
    }
} else {
    // Méthode non autorisée
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Use POST.'
    ]);
}
?>
