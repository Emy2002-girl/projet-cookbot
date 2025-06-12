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
                    session_start();
                    $_SESSION['user_id'] = $user['ID_UTILISATEUR'];
                    $_SESSION['user_name'] = $user['NOM'];
                    $_SESSION['user_prenom'] = $user['PRENOM'];
                    $_SESSION['user_email'] = $user['EMAIL'];
                    $_SESSION['user_role'] = $user['ROLE'];
                    $_SESSION['user_subscription'] = $user['TYPE_ABONNE'];
                    
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

    public function updateProfile($id, $nom, $prenom, $email) {
        try {
            // Vérifier si l'email existe déjà pour un autre utilisateur
            $query = "SELECT ID_UTILISATEUR FROM " . $this->table_name . " WHERE EMAIL = :email AND ID_UTILISATEUR != :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return [
                    'success' => false,
                    'message' => 'Cet email est déjà utilisé par un autre utilisateur'
                ];
            }

            // Mettre à jour le profil
            $query = "UPDATE " . $this->table_name . " 
                     SET NOM = :nom, PRENOM = :prenom, EMAIL = :email 
                     WHERE ID_UTILISATEUR = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Profil mis à jour avec succès!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ];
        }
    }
}
?>
