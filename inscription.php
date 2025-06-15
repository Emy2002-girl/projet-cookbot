<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'user.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $database = new Database();
        $db = $database->getConnection();
        $utilisateur = new Utilisateur($db);

        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $mot_de_passe = $_POST['mot_de_passe'];
        $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];
        $id_abonnement = $_POST['id_abonnement'] ?? 1;

        // Validation des données
        if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
            $message = 'Tous les champs obligatoires doivent être remplis';
            $message_type = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Format d\'email invalide';
            $message_type = 'error';
        } elseif (strlen($mot_de_passe) < 6) {
            $message = 'Le mot de passe doit contenir au moins 6 caractères';
            $message_type = 'error';
        } elseif ($mot_de_passe !== $confirmer_mot_de_passe) {
            $message = 'Les mots de passe ne correspondent pas';
            $message_type = 'error';
        } else {
            $result = $utilisateur->inscrire($nom, $prenom, $email, $mot_de_passe, $id_abonnement);
            $message = $result['message'];
            $message_type = $result['success'] ? 'success' : 'error';
            
            if ($result['success']) {
                // Connecter l'utilisateur
                session_start();
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['user_name'] = $prenom . ' ' . $nom;
                $_SESSION['user_role'] = 'utilisateur';
                
                // Rediriger vers la page de configuration
                header('Location: config_profil.php');
                exit();
            }
        }
    } catch (Exception $e) {
        $message = 'Erreur: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Récupérer les types d'abonnements
try {
    $database = new Database();
    $db = $database->getConnection();
    $utilisateur = new Utilisateur($db);
    $abonnements = $utilisateur->getAbonnements();
} catch (Exception $e) {
    $abonnements = [];
    if (empty($message)) {
        $message = 'Erreur lors de la récupération des abonnements: ' . $e->getMessage();
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - CookBot MasterChef</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #10b981 0%, #10b981 100%);
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
            max-width: 500px;
        }
        .logo { text-align: center; margin-bottom: 30px; }
        .logo h1 {
            font-size: 2.5em;
            display: flex;
            margin-bottom: 10px;
            gap: 10px;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .logo h1 .cook { color: #10b981; }
        .logo h1 .bot { color: #333; margin-left: -5px; }
        .logo p { color: #666; }
        .form-group { margin-bottom: 20px; }
        .form-row { display: flex; gap: 15px; }
        .form-row .form-group { flex: 1; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input[type="text"], input[type="email"], input[type="password"], select {
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e1e5e9; 
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus { 
            outline: none; 
            border-color: #10b981; 
        }
        button { 
            width: 100%; 
            padding: 15px; 
            background: linear-gradient(135deg,rgb(30, 135, 100) 0%, #10b981 100%);
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
        .links a { color: #10b981; text-decoration: none; font-weight: 500; }
        .links a:hover { text-decoration: underline; }
        .abonnement-info { 
            background: #f8f9fa; 
            padding: 10px; 
            border-radius: 5px; 
            margin-top: 5px; 
            font-size: 14px; 
            color: #666; 
        }
        .required { color: #10b981; }
        @media (max-width: 576px) {
            .container { padding: 20px; }
            .logo h1 { font-size: 2em; }
            .form-row { flex-direction: column; gap: 0; }
        }
    </style>
</head>
<body>
<div class="container">
        <div class="logo">
            <div style="display: flex;justify-content: center;align-items: center;gap: 20px;font-size: 27px;font-weight: 600;"><img src="images/cutlery.png" alt="" srcset="" style="width:47px">
            <span style="color:#10b981">Cook</span><span style="margin-left:-11px">Bot</span></div>
            <p>Votre assistant culinaire personnel</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom <span class="required">*</span></label>
                    <input type="text" id="nom" name="nom" required 
                           value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>"
                           placeholder="Votre nom">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom <span class="required">*</span></label>
                    <input type="text" id="prenom" name="prenom" required 
                           value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>"
                           placeholder="Votre prénom">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       placeholder="votre@email.com">
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe <span class="required">*</span></label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="6"
                       placeholder="Au moins 6 caractères">
                <div class="abonnement-info">
                    Le mot de passe doit contenir au moins 6 caractères
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirmer_mot_de_passe">Confirmer le mot de passe <span class="required">*</span></label>
                <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required minlength="6"
                       placeholder="Répétez votre mot de passe">
            </div>
            
            <div class="form-group">
                <label for="id_abonnement">Type d'abonnement</label>
                <select id="id_abonnement" name="id_abonnement">
                    <?php if (!empty($abonnements)): ?>
                        <?php foreach ($abonnements as $abonnement): ?>
                            <option value="<?php echo $abonnement['ID_ABONNEMENT']; ?>" 
                                    <?php echo (isset($_POST['id_abonnement']) && $_POST['id_abonnement'] == $abonnement['ID_ABONNEMENT']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($abonnement['TYPE_ABONNE']); ?>
                                <?php if ($abonnement['ID_ABONNEMENT'] == 1): ?>
                                    - Gratuit
                                <?php elseif ($abonnement['ID_ABONNEMENT'] == 2): ?>
                                    - Premium
                                <?php elseif ($abonnement['ID_ABONNEMENT'] == 3): ?>
                                    - Pro
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1">Gratuit</option>
                    <?php endif; ?>
                </select>
                <div class="abonnement-info">
                    L'abonnement gratuit vous permet de créer jusqu'à 10 recettes
                </div>
            </div>
            
            <button type="submit">S'inscrire</button>
        </form>
        
        <div class="links">
            <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
        </div>
    </div>

    <script>
        // Validation côté client
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('mot_de_passe').value;
            const confirmPassword = document.getElementById('confirmer_mot_de_passe').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères');
                return false;
            }
        });
    </script>
</body>
</html>
