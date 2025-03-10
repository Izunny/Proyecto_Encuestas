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
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $preguntas = $_POST['preguntas'] ?? [];

            // Guardar encuesta y obtener ID
            $idEncuesta = $this->model->agregar($titulo, $descripcion);

            if ($idEncuesta) {
                foreach ($preguntas as $index => $pregunta) {
                    $texto = $pregunta['titulo'];
                    $tipo = $pregunta['tipo'];
                    $requerida = isset($pregunta['requerida']) ? 1 : 0;

                    // Guardar la pregunta y obtener su ID
                    $idPregunta = $this->model->agregarPregunta($idEncuesta, $texto, $tipo, $requerida);

                    if ($tipo === 'opcion_unica' || $tipo === 'opcion_multiple') {
                        $opciones = $pregunta['opciones'] ?? [];
                        foreach ($opciones as $opcion) {
                            $this->model->agregarOpcion($idPregunta, $opcion);
                        }
                    }
                }
            }

            header("Location: /dashboard.php");
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
