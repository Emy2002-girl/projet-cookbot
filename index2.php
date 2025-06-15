<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookBot - Bienvenue</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }
        
        .main-content {
            padding: 0 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-message {
            background-color: #f0fdf4;
            border-radius: 10px;
            padding: 30px;
            margin: 40px auto;
            max-width: 800px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-left: 5px solid #10b981;
        }
        
        .welcome-message h2 {
            color: #10b981;
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .welcome-message p {
            color: #4b5563;
            font-size: 16px;
            margin-bottom: 25px;
        }
        
        .btn-primary {
            display: inline-block;
            padding: 12px 24px;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        // Rediriger vers la page de connexion si non connecté
        header('Location: login.php');
        exit();
    }

    // Récupérer les informations de l'utilisateur
    $user_email = $_SESSION['user_email'] ?? '';
    $user_name = $_SESSION['user_name'] ?? '';
    $user_prenom = $_SESSION['user_prenom'] ?? '';
    $config_complete = isset($_GET['config_complete']) && $_GET['config_complete'] == 1;
    ?>
    
    <div class="main-content">
        <div class="welcome-message">
            <h2>Bienvenue sur CookBot, <?php echo htmlspecialchars($user_prenom); ?> !</h2>
            <p>Découvrez toutes les fonctionnalités de notre plateforme pour améliorer votre expérience culinaire.</p>
            <button class="btn-primary" onclick="window.location.href='homepage.php'">Explorer CookBot</button>
        </div>
    </div>
</body>
</html>
