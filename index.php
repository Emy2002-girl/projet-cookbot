<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacrosChef - Générateur de Recettes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .welcome-message {
            background-color: #f0fdf4;
            border-radius: 10px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #10b981;
            animation: fadeIn 0.5s ease-out;
        }
        
        .welcome-message h2 {
            color: #10b981;
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .welcome-message p {
            color: #4b5563;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .btn-primary {
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();

    // Vérifier si l'utilisateur vient de terminer la configuration
    $config_complete = isset($_GET['config_complete']) && $_GET['config_complete'] == 1;
    ?>

    <?php if ($config_complete): ?>
    <div class="welcome-message">
        <h2>Bienvenue sur CookBot, <?php echo htmlspecialchars($_SESSION['user_name']); ?> !</h2>
        <p>Votre profil est maintenant configuré !</p>
        <button class="btn-primary" onclick="window.location.href='homepage.php'">Commencer Votre Expérience Avec CookBot</button>
    </div>
    <?php endif; ?>
</body>
</html>
