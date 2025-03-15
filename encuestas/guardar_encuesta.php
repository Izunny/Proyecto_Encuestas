<?php
require_once __DIR__ . '/config/database.php';
session_start();

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["status" => "error", "message" => "Error: Usuario no autenticado"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        $encuesta_id = $_POST['idencuesta'] ?? null;
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $fecha = trim($_POST['fecha']);
        $estado = trim($_POST['estado']);
        $idusuario = $_SESSION['idusuario'];

        if ($encuesta_id) {
            $sql = "UPDATE enc_encuestasm 
                    SET nombre = :titulo, descripcion = :descripcion, fecha = STR_TO_DATE(:fecha, '%d/%m/%Y'), activo = :estado 
                    WHERE idencuesta = :idencuesta";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'fecha' => $fecha,
                'estado' => $estado,
                'idencuesta' => $encuesta_id
            ]);
        } else {
            $sql = "INSERT INTO enc_encuestasm (nombre, descripcion, fecha, activo, idusuario) 
                    VALUES (:titulo, :descripcion, STR_TO_DATE(:fecha, '%d/%m/%Y'), :estado, :idusuario)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'fecha' => $fecha,
                'estado' => $estado,
                'idusuario' => $idusuario
            ]);
            $encuesta_id = $pdo->lastInsertId();
        }

        $preguntas = $_POST['preguntas'] ?? [];

        if ($encuesta_id && !empty($preguntas)) {
            $stmt = $pdo->prepare("DELETE FROM enc_pregunta WHERE idencuesta = ?");
            $stmt->execute([$encuesta_id]);
        }

        foreach ($preguntas as $pregunta) {
            $id_tipo_pregunta = in_array($pregunta['tipo'], [1, 2, 3, 4]) ? (int) $pregunta['tipo'] : null;

            if ($id_tipo_pregunta === null) {
                throw new Exception("Error: Tipo de pregunta inválido o no definido.");
            }

            $sql_pregunta = "INSERT INTO enc_pregunta (idencuesta, textopregunta, idtipopregunta, requerida) 
                             VALUES (:idencuesta, :textopregunta, :idtipopregunta, :requerida)";
            $stmt_pregunta = $pdo->prepare($sql_pregunta);
            $stmt_pregunta->execute([
                'idencuesta' => $encuesta_id,
                'textopregunta' => $pregunta['titulo'],
                'idtipopregunta' => $id_tipo_pregunta,
                'requerida' => isset($pregunta['requerida']) ? 1 : 0
            ]);
            $pregunta_id = $pdo->lastInsertId();

            if (in_array($id_tipo_pregunta, [3, 4]) && !empty($pregunta['opciones'])) {
                foreach ($pregunta['opciones'] as $opcion) {
                    $sql_opcion = "INSERT INTO enc_opcion (idpregunta, opcion) VALUES (:idpregunta, :opcion)";
                    $stmt_opcion = $pdo->prepare($sql_opcion);
                    $stmt_opcion->execute([
                        'idpregunta' => $pregunta_id,
                        'opcion' => $opcion
                    ]);
                }
            }
        }

        $pdo->commit();
        echo json_encode(["status" => "success", "message" => "Encuesta actualizada correctamente."]);
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al guardar la encuesta: " . $e->getMessage()]);
    }
}
?>
