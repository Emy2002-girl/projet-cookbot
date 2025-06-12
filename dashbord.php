<?php
require_once 'config/database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Vérifier si l'utilisateur est connecté
if (!$user->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .user-info { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .logout-btn { background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; }
        .logout-btn:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tableau de bord</h1>
        <a href="logout.php" class="logout-btn">Se déconnecter</a>
    </div>
    
    <div class="user-info">
        <h3>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>ID utilisateur:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
    </div>
    
    <div>
        <h3>Contenu protégé</h3>
        <p>Cette page n'est accessible qu'aux utilisateurs connectés.</p>
    </div>
</body>
</html>