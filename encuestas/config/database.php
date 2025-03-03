<?php

$host = 'localhost';
$db   = 'db_encuestas';               
$user = 'root';                      
$pass = '';                          
$charset = 'utf8mb4';

// para entrar a la base de datos  http://localhost/phpmyadmin


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Muestra excepciones en caso de error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Modo de obtención de datos como array asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa consultas preparadas nativas
];

try {
    
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
