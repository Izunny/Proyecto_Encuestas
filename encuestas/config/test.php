<?php
include 'config/database.php'; // Esto busca en "C:\xampp\htdocs\encuestas\includes\db.php"
$stmt = $pdo->query('SELECT VERSION()');
$version = $stmt->fetch();
echo "Versión de MySQL: " . $version['VERSION()'];
?>