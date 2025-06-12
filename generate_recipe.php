<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuration de la base de données - CORRECTION ICI
$host = 'localhost';
$dbname = 'cookbot_recipes'; // Changé de 'diet_advisor' à 'cookbot_recipes'
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

// Récupération des paramètres
$glucides_cible = floatval($_POST['glucides'] ?? 0);
$proteines_cible = floatval($_POST['proteines'] ?? 0);
$lipides_cible = floatval($_POST['lipides'] ?? 0);
$type_repas = $_POST['typeRepas'] ?? '';
$besoin_alimentaire = $_POST['besoinAlimentaire'] ?? '';

// Validation des paramètres
if (empty($type_repas) || $glucides_cible < 0 || $proteines_cible < 0 || $lipides_cible < 0) {
    echo json_encode(['success' => false, 'error' => 'Paramètres invalides']);
    exit;
}

// Fonction pour calculer la distance entre les macros cibles et celles de la recette
function calculateMacroDistance($recipe, $glucides_cible, $proteines_cible, $lipides_cible) {
    $glucides_diff = abs(($recipe['GLUCIDES'] ?? 0) - $glucides_cible);
    $proteines_diff = abs(($recipe['PROTEINES'] ?? 0) - $proteines_cible);
    $lipides_diff = abs(($recipe['LIPIDES'] ?? 0) - $lipides_cible);
    
    // Distance euclidienne pondérée
    return sqrt(
        pow($glucides_diff * 0.4, 2) + 
        pow($proteines_diff * 0.4, 2) + 
        pow($lipides_diff * 0.2, 2)
    );
}

try {
    // Construction de la requête SQL de base
    $sql = "SELECT * FROM recette WHERE TYPE_REPAS = :type_repas AND GLUCIDES IS NOT NULL AND PROTEINES IS NOT NULL AND LIPIDES IS NOT NULL";
    
    // Ajouter des conditions selon le besoin alimentaire
    if (!empty($besoin_alimentaire)) {
        switch ($besoin_alimentaire) {
            case 'vegetarien':
                $sql .= " AND INGREDIENTS NOT LIKE '%poulet%' AND INGREDIENTS NOT LIKE '%boeuf%' AND INGREDIENTS NOT LIKE '%porc%'";
                break;
            case 'vegan':
                $sql .= " AND INGREDIENTS NOT LIKE '%poulet%' AND INGREDIENTS NOT LIKE '%boeuf%' AND INGREDIENTS NOT LIKE '%porc%'";
                $sql .= " AND INGREDIENTS NOT LIKE '%lait%' AND INGREDIENTS NOT LIKE '%oeuf%' AND INGREDIENTS NOT LIKE '%fromage%'";
                break;
            case 'sans_gluten':
                $sql .= " AND INGREDIENTS NOT LIKE '%blé%' AND INGREDIENTS NOT LIKE '%farine%' AND INGREDIENTS NOT LIKE '%pain%'";
                break;
            case 'sans_lactose':
                $sql .= " AND INGREDIENTS NOT LIKE '%lait%' AND INGREDIENTS NOT LIKE '%fromage%' AND INGREDIENTS NOT LIKE '%yaourt%'";
                break;
            case 'low_carb':
                $sql .= " AND GLUCIDES < 20";
                break;
            case 'high_protein':
                $sql .= " AND PROTEINES > 25";
                break;
            case 'keto':
                $sql .= " AND GLUCIDES < 15 AND LIPIDES > 20";
                break;
            case 'paleo':
                $sql .= " AND INGREDIENTS NOT LIKE '%farine%' AND INGREDIENTS NOT LIKE '%sucre%' AND INGREDIENTS NOT LIKE '%pâtes%'";
                break;
        }
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':type_repas', $type_repas);
    $stmt->execute();
    
    $recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($recettes)) {
        echo json_encode(['success' => false, 'error' => 'Aucune recette trouvée pour ces critères']);
        exit;
    }
    
    // Calcul de la distance pour chaque recette et tri
    $recettes_avec_distance = [];
    foreach ($recettes as $recette) {
        $distance = calculateMacroDistance($recette, $glucides_cible, $proteines_cible, $lipides_cible);
        $recette['distance'] = $distance;
        $recettes_avec_distance[] = $recette;
    }
    
    // Tri par distance croissante
    usort($recettes_avec_distance, function($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });
    
    // Retourner les 3 meilleures recettes
    $meilleures_recettes = array_slice($recettes_avec_distance, 0, 3);
    
    // Nettoyer les données pour l'affichage
    foreach ($meilleures_recettes as &$recette) {
        unset($recette['distance']);
        
        // Arrondir les valeurs nutritionnelles
        $recette['GLUCIDES'] = round($recette['GLUCIDES'], 1);
        $recette['PROTEINES'] = round($recette['PROTEINES'], 1);
        $recette['LIPIDES'] = round($recette['LIPIDES'], 1);
        $recette['CALORIES'] = round($recette['CALORIES']);
        
        // Nettoyer le texte des instructions
        $recette['INSTRUCTIONS'] = str_replace(['\r\n', '\n', '\r'], "\n", $recette['INSTRUCTIONS']);
        
        // Ajouter le régime alimentaire si spécifié
        if (!empty($besoin_alimentaire)) {
            switch ($besoin_alimentaire) {
                case 'vegetarien':
                    $recette['REGIME_ALIMENTAIRE'] = 'Végétarien';
                    break;
                case 'vegan':
                    $recette['REGIME_ALIMENTAIRE'] = 'Végétalien';
                    break;
                case 'sans_gluten':
                    $recette['REGIME_ALIMENTAIRE'] = 'Sans gluten';
                    break;
                case 'sans_lactose':
                    $recette['REGIME_ALIMENTAIRE'] = 'Sans lactose';
                    break;
                case 'low_carb':
                    $recette['REGIME_ALIMENTAIRE'] = 'Faible en glucides';
                    break;
                case 'high_protein':
                    $recette['REGIME_ALIMENTAIRE'] = 'Riche en protéines';
                    break;
                case 'keto':
                    $recette['REGIME_ALIMENTAIRE'] = 'Keto';
                    break;
                case 'paleo':
                    $recette['REGIME_ALIMENTAIRE'] = 'Paléo';
                    break;
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'recipes' => $meilleures_recettes,
        'criteria' => [
            'glucides' => $glucides_cible,
            'proteines' => $proteines_cible,
            'lipides' => $lipides_cible,
            'type_repas' => $type_repas,
            'besoin_alimentaire' => $besoin_alimentaire
        ]
    ]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la recherche des recettes: ' . $e->getMessage()]);
}
?>