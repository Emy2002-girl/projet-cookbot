<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'user.php';

$message = '';
$message_type = '';

// Message de confirmation d'inscription
if (isset($_GET['inscrit'])) {
    $message = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
    $message_type = 'success';
}

// Message de déconnexion
if (isset($_GET['deconnecte'])) {
    $message = 'Vous avez été déconnecté avec succès.';
    $message_type = 'success';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $database = new Database();
        $db = $database->getConnection();
        $utilisateur = new Utilisateur($db);

        $email = trim($_POST['email']);
        $mot_de_passe = $_POST['mot_de_passe'];

        $result = $utilisateur->connecter($email, $mot_de_passe);
        $message = $result['message'];
        $message_type = $result['success'] ? 'success' : 'error';
        
        if ($result['success']) {
            header('Location: tableau-de-bord.php');
            exit();
        }
    } catch (Exception $e) {
        $message = 'Erreur: ' . $e->getMessage();
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - CookBot</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #23b585 0%, #E0E0E0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container { 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo div {font-size: 2.5em;display: flex;margin-bottom: 10px;gap: 10px;align-items: center;justify-content: center;font-weight: 600;}
        .logo p { color: #666;margin-left: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input[type="email"], input[type="password"] {
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e1e5e9; 
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus { 
            outline: none; 
            border-color: #667eea; 
        }
        button { 
            width: 100%; 
            padding: 15px; 
            background: linear-gradient(135deg,#10b981 0%, #20c997 100%);
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover { transform: translateY(-2px); }
        .message { 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 8px; 
            font-weight: 500;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #667eea; text-decoration: none; font-weight: 500; }
        .links a:hover { text-decoration: underline; }
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .remember-me input {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <div><img src="images/cutlery.png" width="47px"><span style="color:#10b981">Cook</span><span style="margin-left: -11px;">Bot</span></div>
            <p>Bon retour parmi nous !</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>
            
            <button type="submit">Se connecter</button>
        </form>
        
        <div class="links">
            <p>Pas encore de compte ? <a href="inscription.php" style="color:#10B981">S'inscrire</a></p>
            <p><a href="mot-de-passe-oublie.php"  style="color:#10B981">Mot de passe oublié ?</a></p>
        </div>
    </div>
</body>
</html>