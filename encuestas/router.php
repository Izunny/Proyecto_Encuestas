<?php
require './config/database.php';
require 'app/controllers/EncuestasController.php';

$controller = new EncuestasController($pdo);

$request = $_GET['url'] ?? 'dashboard';

switch ($request) {
    case 'encuestas':
        $controller->index();
        break;
    case 'encuestas/agregar':
        $controller->agregar();
        break;
    case 'encuestas/eliminar':
        $controller->eliminar();
        break;
    default:
        echo "Página no encontrada.";
}
?>
