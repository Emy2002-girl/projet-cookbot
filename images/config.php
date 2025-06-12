<?php

$datasource = 'mysql:host=localhost;dbname=cookbot_db';
$user = 'root';
$password = '';
try{
   $pdo = new PDO ($datasource,$user,$password  );
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Failed " .$e->getMessage();
}
?>
