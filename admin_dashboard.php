<?php
session_start();
require_once 'config.php';
require_once 'user.php';
// Vérifier si l'utilisateur est connecté et est un administrateur
$database = new Database();
$db = $database->getConnection();
$user = new Utilisateur($db);

if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: login.php');
    exit();
}

// Récupérer les statistiques pour le tableau de bord
$total_users = $user->getTotalUsers();
$total_recipes = $user->getTotalRecipesGenerated();
$paid_users = $user->getPaidUsers();

// Récupérer les ingrédients les plus recherchés
$top_ingredients = $user->getTopIngredients(4);

// Récupérer l'activité récente
$recent_activity = $user->getRecentActivity();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin - CookBot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            background-color: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
        }
        
        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 20px;
        }
        
        .logo img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
        
        .logo-text {
            font-weight: 600;
            font-size: 18px;
        }
        
        .logo-text span {
            color: #10B981;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #666;
            text-decoration: none;
            transition: all 0.3s;
            margin-bottom: 5px;
        }
        
        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .menu-item.active {
            background-color: #f0f9f6;
            color: #10B981;
            border-left: 3px solid #10B981;
        }
        
        .menu-item:hover {
            background-color: #f5f5f5;
        }
        
        .logout {
            margin-top: auto;
            padding: 12px 20px;
            color: #666;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logout i {
            margin-right: 10px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 220px;
            width: calc(100% - 220px);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .welcome {
            font-size: 24px;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #10B981;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }
        
        .user-name {
            font-weight: 500;
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            display: flex;
            align-items: center;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
            color: white;
        }
        
        .blue-bg {
            background-color: #4f46e5;
        }
        
        .orange-bg {
            background-color: #f97316;
        }
        
        .green-bg {
            background-color: #10B981;
        }
        
        .stat-info {
            flex: 1;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 14px;
        }
        
        /* Sections */
        .section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
        }
        
        /* Ingredients Chart */
        .ingredients-chart {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .ingredient-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .ingredient-name {
            display: flex;
            align-items: center;
        }
        
        .ingredient-icon {
            margin-right: 10px;
            font-size: 16px;
        }
        
        /* Users Table */
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .users-table th, .users-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .users-table th {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }
        
        .users-table tr:last-child td {
            border-bottom: none;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .active {
            background-color: #d1fae5;
            color: #10B981;
        }
        
        .inactive {
            background-color: #fee2e2;
            color: #ef4444;
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            margin-right: 5px;
        }
        
        .action-btn:hover {
            color: #10B981;
        }
        
        /* Subscription Plans */
        .plans-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .plans-table th, .plans-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .plans-table th {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }
        
        .plans-table tr:last-child td {
            border-bottom: none;
        }
        
        .add-plan-btn {
            background-color: #10B981;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 14px;
        }
        
        .add-plan-btn i {
            margin-right: 5px;
        }
        
        /* Recent Users */
        .recent-users {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .user-item {
            display: flex;
            align-items: center;
        }
        
        .user-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            overflow: hidden;
        }
        
        .user-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-details {
            flex: 1;
        }
        
        .user-name {
            font-weight: 500;
            margin-bottom: 3px;
        }
        
        .user-time {
            font-size: 12px;
            color: #6b7280;
        }
        
        /* Search and Filter */
        .search-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .search-input {
            flex: 1;
            padding: 8px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .filter-select {
            padding: 8px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 14px;
            background-color: white;
        }
        
        /* Content List */
        .content-list {
            list-style: none;
            padding: 0;
        }
        
        .content-list li {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .content-list li:last-child {
            border-bottom: none;
        }
        
        .content-list li span:last-child {
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="images/cutlery.png" alt="CookBot Logo">
            <div class="logo-text"><span>Cook</span>Bot</div>
        </div>
        
        <a href="admin_dashboard.php" class="menu-item active">
            <i class="fas fa-chart-line"></i>
            Tableau de bord
        </a>
        
        <a href="admin_users.php" class="menu-item">
            <i class="fas fa-users"></i>
            Utilisateurs
        </a>
        
        <a href="admin_recipes.php" class="menu-item">
            <i class="fas fa-utensils"></i>
            Recettes
        </a>
        
        <a href="admin_subscriptions.php" class="menu-item">
            <i class="fas fa-credit-card"></i>
            Abonnements
        </a>
        
        <a href="admin_settings.php" class="menu-item">
            <i class="fas fa-cog"></i>
            Paramètres
        </a>
        
        <a href="logout.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
            Se déconnecter
        </a>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="welcome">
                Bonjour, <span>Admin</span>
            </div>
            
            <div class="user-info">
                <?php 
                $user_name = $_SESSION['user_name'] ?? '';
                $user_prenom = $_SESSION['user_prenom'] ?? '';
                $initial = strtoupper(substr($user_prenom, 0, 1));
                ?>
                <div class="user-avatar"><?php echo $initial; ?></div>
                <div class="user-name"><?php echo htmlspecialchars($user_prenom . ' ' . $user_name); ?></div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon blue-bg">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($total_users); ?></div>
                    <div class="stat-label">Utilisateurs totaux</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange-bg">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($total_recipes); ?></div>
                    <div class="stat-label">Recettes générées</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon green-bg">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($paid_users); ?></div>
                    <div class="stat-label">Utilisateurs payants</div>
                </div>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <!-- Left Column -->
            <div>
                <!-- Ingredients Section -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">Ingrédients les plus recherchés</div>
                    </div>
                    
                    <div class="ingredients-chart">
                        <?php
                        // Icônes pour les ingrédients courants (version simplifiée)
                        $ingredient_icons = [
                            'carotte' => '🥕',
                            'oeuf' => '🥚',
                            'saumon' => '🐟',
                            'poulet' => '🍗',
                            'tomate' => '🍅',
                            'oignon' => '🧅',
                            'ail' => '🧄',
                            'fromage' => '🧀',
                            'pain' => '🍞',
                            'pomme' => '🍎',
                            'banane' => '🍌',
                            'citron' => '🍋',
                            'boeuf' => '🥩',
                            'porc' => '🥓',
                            'riz' => '🍚',
                            'pâtes' => '🍝'
                        ];
                        
                        if (!empty($top_ingredients)) {
                            foreach ($top_ingredients as $ingredient) {
                                $nom = strtolower($ingredient['nom']);
                                $icon = isset($ingredient_icons[$nom]) ? $ingredient_icons[$nom] : '🍽️';
                                $total = $ingredient['total'] ?? 0;
                                
                                echo '<div class="ingredient-item">';
                                echo '<div class="ingredient-name">';
                                echo '<div class="ingredient-icon">' . $icon . '</div>';
                                echo '<span>' . htmlspecialchars(ucfirst($nom)) . '</span>';
                                echo '</div>';
                                echo '<div>' . number_format($total) . '</div>';
                                echo '</div>';
                            }
                        } else {
                            // Données de démonstration si aucun ingrédient n'est trouvé
                            $demo_ingredients = [
                                ['nom' => 'Carotte', 'icon' => '🥕', 'total' => 1120],
                                ['nom' => 'Oeuf', 'icon' => '🥚', 'total' => 1050],
                                ['nom' => 'Saumon', 'icon' => '🐟', 'total' => 980],
                                ['nom' => 'Poulet', 'icon' => '🍗', 'total' => 775]
                            ];
                            
                            foreach ($demo_ingredients as $ingredient) {
                                echo '<div class="ingredient-item">';
                                echo '<div class="ingredient-name">';
                                echo '<div class="ingredient-icon">' . $ingredient['icon'] . '</div>';
                                echo '<span>' . htmlspecialchars($ingredient['nom']) . '</span>';
                                echo '</div>';
                                echo '<div>' . number_format($ingredient['total']) . '</div>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Users Management Section -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">Gestion des utilisateurs</div>
                        <div class="search-filter">
                            <input type="text" class="search-input" placeholder="Rechercher...">
                            <select class="filter-select">
                                <option>Tous rôles</option>
                                <option>Utilisateur</option>
                                <option>Administrateur</option>
                            </select>
                            <select class="filter-select">
                                <option>Tous statuts</option>
                                <option>Actif</option>
                                <option>Inactif</option>
                            </select>
                        </div>
                    </div>
                    
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Date inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Récupérer les utilisateurs récents
                            $recent_users = $user->getRecentUsers(5);
                            if (!empty($recent_users)) {
                                foreach ($recent_users as $recent_user) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($recent_user['prenom'] . ' ' . $recent_user['nom']) . '</td>';
                                    echo '<td>' . htmlspecialchars($recent_user['email']) . '</td>';
                                    echo '<td>Utilisateur</td>';
                                    echo '<td><span class="status active">Actif</span></td>';
                                    echo '<td>' . htmlspecialchars(date('d/m/Y', strtotime($recent_user['created_at']))) . '</td>';
                                    echo '<td>
                                            <button class="action-btn"><i class="fas fa-eye"></i></button>
                                            <button class="action-btn"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn"><i class="fas fa-trash"></i></button>
                                          </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6">Aucun utilisateur trouvé</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Subscription Plans Section -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">Plans d'abonnement</div>
                        <button class="add-plan-btn">
                            <i class="fas fa-plus"></i>
                            Ajouter un plan
                        </button>
                    </div>
                    
                    <table class="plans-table">
                        <thead>
                            <tr>
                                <th>Nom du plan</th>
                                <th>Prix</th>
                                <th>Durée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Basic</td>
                                <td>19,99 €</td>
                                <td>1 mois</td>
                                <td>
                                    <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Pro</td>
                                <td>39,99 €</td>
                                <td>3 mois</td>
                                <td>
                                    <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Premium</td>
                                <td>59,99 €</td>
                                <td>1 an</td>
                                <td>
                                    <button class="action-btn"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Right Column -->
            <div>
                <!-- Recent Users Section -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">Utilisateurs récents</div>
                    </div>
                    
                    <div class="recent-users">
                        <?php
                        // Récupérer les utilisateurs récents
                        $recent_users = $user->getRecentUsers(5);
                        if (!empty($recent_users)) {
                            foreach ($recent_users as $recent_user) {
                                echo '<div class="user-item">';
                                echo '<div class="user-pic">';
                                echo '<img src="images/user.png" alt="User Profile">';
                                echo '</div>';
                                echo '<div class="user-details">';
                                echo '<div class="user-name">' . htmlspecialchars($recent_user['prenom'] . ' ' . $recent_user['nom']) . '</div>';
                                echo '<div class="user-time">' . htmlspecialchars(date('d/m/Y', strtotime($recent_user['created_at']))) . '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="user-item">Aucun utilisateur récent</div>';
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Activity Section -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-title">Activité récente</div>
                    </div>
                    
                    <ul class="content-list">
                        <li>
                            <span>Nouvel utilisateur inscrit</span>
                            <span>Il y a 10 min</span>
                        </li>
                        <li>
                            <span>Nouvelle recette ajoutée</span>
                            <span>Il y a 25 min</span>
                        </li>
                        <li>
                            <span>Commentaire signalé</span>
                            <span>Il y a 1 heure</span>
                        </li>
                        <li>
                            <span>Mise à jour du système</span>
                            <span>Il y a 3 heures</span>
                        </li>
                        <li>
                            <span>Nouvelle fonctionnalité activée</span>
                            <span>Il y a 5 heures</span>
                        </li>
                    </ul>
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="admin_activity.php" style="text-decoration: none; color: #10b981;">Voir toute l'activité</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



