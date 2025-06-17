<?php
// Script pour créer un utilisateur admin avec mot de passe hashé
require_once 'config.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Informations admin
    $email = 'admin@gmail.com';
    $password = 'admin123';
    $nom = 'Admin';
    $prenom = 'Super';
    $role = 'admin';
    
    // Hasher le mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Vérifier si l'admin existe déjà
    $check_query = "SELECT ID_UTILISATEUR FROM utilisateur WHERE EMAIL = :email";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':email', $email);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 0) {
        // Mettre à jour le mot de passe existant
        $update_query = "UPDATE utilisateur SET 
                        MOT_DE_PASSE = :password,
                        ROLE = :role,
                        NOM = :nom,
                        PRENOM = :prenom
                        WHERE EMAIL = :email";
        
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':password', $password_hash);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':nom', $nom);
        $update_stmt->bindParam(':prenom', $prenom);
        $update_stmt->bindParam(':email', $email);
        
        if ($update_stmt->execute()) {
            echo "✅ Utilisateur admin mis à jour avec succès !<br>";
            echo "📧 Email: " . $email . "<br>";
            echo "🔑 Mot de passe: " . $password . "<br>";
            echo "🔐 Hash: " . $password_hash . "<br>";
        } else {
            echo "❌ Erreur lors de la mise à jour de l'admin";
        }
    } else {
        // Créer un nouvel utilisateur admin
        $insert_query = "INSERT INTO utilisateur (
                        NOM, PRENOM, EMAIL, MOT_DE_PASSE, ROLE, 
                        ID_ABONNEMENT, DATE_CREATION, PROFIL_COMPLETE
                        ) VALUES (
                        :nom, :prenom, :email, :password, :role, 
                        1, NOW(), 1
                        )";
        
        $insert_stmt = $db->prepare($insert_query);
        $insert_stmt->bindParam(':nom', $nom);
        $insert_stmt->bindParam(':prenom', $prenom);
        $insert_stmt->bindParam(':email', $email);
        $insert_stmt->bindParam(':password', $password_hash);
        $insert_stmt->bindParam(':role', $role);
        
        if ($insert_stmt->execute()) {
            echo "✅ Utilisateur admin créé avec succès !<br>";
            echo "📧 Email: " . $email . "<br>";
            echo "🔑 Mot de passe: " . $password . "<br>";
            echo "🔐 Hash: " . $password_hash . "<br>";
            echo "🆔 ID: " . $db->lastInsertId() . "<br>";
        } else {
            echo "❌ Erreur lors de la création de l'admin";
        }
    }
    
    // Vérifier la connexion
    echo "<br>🧪 Test de vérification du mot de passe:<br>";
    if (password_verify($password, $password_hash)) {
        echo "✅ Le mot de passe peut être vérifié correctement !";
    } else {
        echo "❌ Problème avec la vérification du mot de passe";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de base de données: " . $e->getMessage();
}
?>
