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
        $stmt = $this->pdo->prepare("INSERT INTO enc_encuestasm (nombre, descripcion, fecha, idusuario, activo) VALUES (?, ?, NOW(), 1, 'S')");
        $stmt->execute([$titulo, $descripcion]);
        return $this->pdo->lastInsertId();
    }
    

    public function agregarPregunta($encuesta_id, $pregunta, $tipo, $requerida) {
        $sql = "INSERT INTO preguntas (encuesta_id, texto, tipo, requerida) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$encuesta_id, $pregunta, $tipo, $requerida]);
    }

    public function agregarOpcion($idPregunta, $opcion) {
        $stmt = $this->pdo->prepare("INSERT INTO opciones (pregunta_id, texto) VALUES (?, ?)");
        return $stmt->execute([$idPregunta, $opcion]);
    }

    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM encuestas WHERE idencuesta = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM encuestas WHERE idencuesta = ?");
        return $stmt->execute([$id]);
    }
}
?>
