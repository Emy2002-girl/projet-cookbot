<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'cookbot_recipes'; // Nom de la base de données créée par le script
$username = 'root';  // Remplacez par votre nom d'utilisateur
$password = '';      // Remplacez par votre mot de passe

try {
    // Création d'une nouvelle connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuration pour que PDO lance des exceptions en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configuration pour que PDO retourne des tableaux associatifs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // En cas d'erreur de connexion
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>