// Ajouter ces méthodes à votre classe User

public function isAdmin() {
    // Vérifier si l'utilisateur connecté est un administrateur
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    $query = "SELECT role FROM users WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $_SESSION['user_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['role'] === 'admin';
    }
    
    return false;
}

public function getTotalUsers() {
    $query = "SELECT COUNT(*) as total FROM users";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total'];
}

public function getNewUsersThisMonth() {
    $query = "SELECT COUNT(*) as total FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total'];
}

public function getActiveUsers() {
    // Utilisateurs actifs au cours des 30 derniers jours
    $query = "SELECT COUNT(*) as total FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total'];
}

public function getRecentUsers($limit = 5) {
    $query = "SELECT id, nom, prenom, email, created_at FROM users ORDER BY created_at DESC LIMIT ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}