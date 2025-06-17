<?php
class Utilisateur {
    private $conn;
    private $table_name = "utilisateur";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function inscrire($nom, $prenom, $email, $mot_de_passe, $id_abonnement = 1) {
        try {
            // Vérifier si l'email existe déjà
            $query = "SELECT ID_UTILISATEUR FROM " . $this->table_name . " WHERE EMAIL = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return [
                    'success' => false,
                    'message' => 'Cet email est déjà utilisé'
                ];
            }

            // Hasher le mot de passe
            $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur avec PRENOM
            $query = "INSERT INTO " . $this->table_name . " 
                     (NOM, PRENOM, EMAIL, MOT_DE_PASSE, ID_ABONNEMENT, ROLE, DATE_CREATION) 
                     VALUES (:nom, :prenom, :email, :mot_de_passe, :id_abonnement, 'utilisateur', NOW())";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe_hash);
            $stmt->bindParam(':id_abonnement', $id_abonnement);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Inscription réussie!',
                    'user_id' => $this->conn->lastInsertId()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de l\'inscription'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    public function connecter($email, $mot_de_passe) {
        try {
            $query = "SELECT u.*, a.TYPE_ABONNE 
                     FROM " . $this->table_name . " u 
                     LEFT JOIN abonnement a ON u.ID_ABONNEMENT = a.ID_ABONNEMENT 
                     WHERE u.EMAIL = :email";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($mot_de_passe, $user['MOT_DE_PASSE'])) {
                    // Démarrer la session
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user_id'] = $user['ID_UTILISATEUR'];
                    $_SESSION['user_name'] = $user['NOM'];
                    $_SESSION['user_prenom'] = $user['PRENOM'];
                    $_SESSION['user_email'] = $user['EMAIL'];
                    $_SESSION['user_role'] = $user['ROLE'];
                    $_SESSION['user_subscription'] = $user['TYPE_ABONNE'] ?? 'gratuit';
                    
                    return [
                        'success' => true,
                        'message' => 'Connexion réussie!',
                        'user' => $user
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Mot de passe incorrect'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Email non trouvé'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    // ===== MÉTHODES POUR L'ADMIN =====
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function getTotalUsers() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE ROLE != 'admin'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTotalRecipesGenerated() {
        try {
            $query = "SELECT COUNT(*) as total FROM recette";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getPaidUsers() {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " u 
                     LEFT JOIN abonnement a ON u.ID_ABONNEMENT = a.ID_ABONNEMENT 
                     WHERE a.TYPE_ABONNE != 'gratuit' AND a.TYPE_ABONNE IS NOT NULL";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTopIngredients($limit = 5) {
        try {
            $query = "SELECT i.NOM as nom, COUNT(*) as total 
                     FROM ingredient i 
                     JOIN recette_ingredient ri ON i.ID_INGREDIENT = ri.ID_INGREDIENT 
                     GROUP BY i.ID_INGREDIENT, i.NOM 
                     ORDER BY total DESC 
                     LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getRecentUsers($limit = 5) {
        try {
            $query = "SELECT NOM as nom, PRENOM as prenom, EMAIL as email, DATE_CREATION as created_at 
                     FROM " . $this->table_name . " 
                     WHERE ROLE != 'admin' 
                     ORDER BY DATE_CREATION DESC 
                     LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getRecentActivity() {
        // Méthode pour récupérer l'activité récente
        // Pour l'instant, retourne un tableau vide
        // À implémenter selon vos besoins spécifiques
        return [
            ['action' => 'Nouvel utilisateur inscrit', 'time' => 'Il y a 10 min'],
            ['action' => 'Nouvelle recette ajoutée', 'time' => 'Il y a 25 min'],
            ['action' => 'Commentaire signalé', 'time' => 'Il y a 1 heure'],
            ['action' => 'Mise à jour du système', 'time' => 'Il y a 3 heures'],
            ['action' => 'Nouvelle fonctionnalité activée', 'time' => 'Il y a 5 heures']
        ];
    }

    public function getAbonnements() {
        try {
            $query = "SELECT * FROM abonnement WHERE STATUS = 'Actif' ORDER BY ID_ABONNEMENT ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT u.*, a.TYPE_ABONNE 
                     FROM " . $this->table_name . " u 
                     LEFT JOIN abonnement a ON u.ID_ABONNEMENT = a.ID_ABONNEMENT 
                     WHERE u.ID_UTILISATEUR = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateProfile($user_id, $data) {
        try {
            // Vérifier si l'utilisateur existe
            $query = "SELECT ID_UTILISATEUR FROM " . $this->table_name . " WHERE ID_UTILISATEUR = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                return [
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ];
            }

            // Mettre à jour le profil utilisateur
            $query = "UPDATE " . $this->table_name . " SET 
                     GENRE = :genre,
                     AGE = :age,
                     TAILLE = :taille,
                     POIDS = :poids,
                     NIVEAU_CUISINE = :niveau_cuisine,
                     RESTRICTIONS_ALIMENTAIRES = :restrictions,
                     PROFIL_COMPLETE = 1
                     WHERE ID_UTILISATEUR = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':genre', $data['genre']);
            $stmt->bindParam(':age', $data['age']);
            $stmt->bindParam(':taille', $data['taille']);
            $stmt->bindParam(':poids', $data['poids']);
            $stmt->bindParam(':niveau_cuisine', $data['niveau_cuisine']);
            $stmt->bindParam(':restrictions', $data['restrictions']);
            $stmt->bindParam(':id', $user_id);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Profil mis à jour avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du profil'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }

    // ===== MÉTHODES UTILITAIRES =====
    
    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public function getCurrentUserEmail() {
        return $_SESSION['user_email'] ?? null;
    }

    public function getCurrentUserName() {
        return $_SESSION['user_name'] ?? null;
    }

    public function getCurrentUserPrenom() {
        return $_SESSION['user_prenom'] ?? null;
    }

    public function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }

    public function getCurrentUserSubscription() {
        return $_SESSION['user_subscription'] ?? null;
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Détruire toutes les variables de session
        $_SESSION = array();
        
        // Détruire la session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Détruire la session
        session_destroy();
        
        return true;
    }
}
?>
