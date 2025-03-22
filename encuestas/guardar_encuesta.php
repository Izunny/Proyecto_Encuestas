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

        $idEncuesta = $_POST['idencuesta'] ?? null;
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $fecha = trim($_POST['fecha']);
        $estado = trim($_POST['estado']);
        $idusuario = $_SESSION['idusuario'];

        if ($idEncuesta) {
            $sql = "UPDATE enc_encuestasm 
                    SET nombre = :titulo, descripcion = :descripcion, fecha = STR_TO_DATE(:fecha, '%d/%m/%Y'), activo = :estado 
                    WHERE idencuesta = :idencuesta";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'fecha' => $fecha,
                'estado' => $estado,
                'idencuesta' => $idEncuesta
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
            $idEncuesta = $pdo->lastInsertId();
        }

        $preguntas = $_POST['preguntas'] ?? [];

        if ($idEncuesta && !empty($preguntas)) {
            $stmt = $pdo->prepare("DELETE FROM enc_pregunta WHERE idencuesta = ?");
            $stmt->execute([$idEncuesta]);
        }

        // tipos de pregunta 
        $tipos_pregunta = [
            "texto" => 1,
            "texto_abierto" => 2,
            "opcion_unica" => 3,
            "opcion_multiple" => 4
        ];

        foreach ($preguntas as $pregunta) {
            $tipo_pregunta = $pregunta['tipo'] ?? null;

            // Convertimos el tipo de pregunta de texto a número si es necesario
            if (isset($tipos_pregunta[$tipo_pregunta])) {
                $id_tipo_pregunta = $tipos_pregunta[$tipo_pregunta];
            } else {
                $id_tipo_pregunta = in_array($tipo_pregunta, [1, 2, 3, 4]) ? (int) $tipo_pregunta : null;
            }

            if ($id_tipo_pregunta === null) {
                throw new Exception("Error: Tipo de pregunta inválido o no definido. Tipo recibido: " . $tipo_pregunta);
            }

            $sql_pregunta = "INSERT INTO enc_pregunta (idencuesta, textopregunta, idtipopregunta, requerida) 
                             VALUES (:idencuesta, :textopregunta, :idtipopregunta, :requerida)";
            $stmt_pregunta = $pdo->prepare($sql_pregunta);
            $stmt_pregunta->execute([
                'idencuesta' => $idEncuesta,
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
    echo json_encode(["status" => "success", "message" => "Encuesta guardada correctamente."]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => "Error al guardar la encuesta: " . $e->getMessage()]);
}
}
?>
