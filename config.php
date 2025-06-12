<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'cookbot_recipes';  // Nom de votre base de données
    private $username = 'root';       // Nom d'utilisateur MySQL (par défaut avec XAMPP)
    private $password = '';           // Mot de passe MySQL (souvent vide avec XAMPP)
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            die("Erreur de connexion à la base de données: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>