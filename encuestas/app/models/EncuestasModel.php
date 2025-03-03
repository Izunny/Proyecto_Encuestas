<?php
class EncuestasModel {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function obtenerTodas() {
        $stmt = $this->pdo->query("SELECT * FROM encuestas");
        return $stmt->fetchAll();
    }

    public function agregar($titulo, $descripcion) {
        $stmt = $this->pdo->prepare("INSERT INTO encuestas (titulo, descripcion) VALUES (?, ?)");
        return $stmt->execute([$titulo, $descripcion]);
    }

    public function agregarPregunta($encuesta_id, $pregunta, $respuesta_a, $respuesta_b) {
        $sql = "INSERT INTO preguntas (encuesta_id, pregunta, respuesta_a, respuesta_b) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$encuesta_id, $pregunta, $respuesta_a, $respuesta_b]);
    }
    

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM encuestas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM encuestas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
