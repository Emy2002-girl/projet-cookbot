<?php
require_once 'config/database.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$user->logout();
header('Location: login.php?logout=1');
exit();
?>