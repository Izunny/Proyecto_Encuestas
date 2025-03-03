<?php
require 'app/models/EncuestasModel.php';

class EncuestasController {
    private $model;

    public function __construct($pdo) {
        $this->model = new EncuestasModel($pdo);
    }

    public function index() {
        $encuestas = $this->model->obtenerTodas();
        include 'app/views/encuestas/lista.php';
    }

    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->agregar($_POST['titulo'], $_POST['descripcion']);
            header("Location: router.php?url=encuestas");
            exit;
            
        }
        include 'app/views/encuestas/agregar.php';
    }

    public function eliminar() {
        $this->model->eliminar($_GET['id']);
        header("Location: /dashboard.php");
    }
}
?>
