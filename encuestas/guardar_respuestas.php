<?php
require 'config/database.php';
session_start();

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["status" => "error", "message" => "Error: Usuario no autenticado"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();
        $idusuario = $_SESSION['idusuario'];
        $idencuesta = $_POST['idencuesta'];
        $respuestas = $_POST['respuestas'] ?? [];

        if (empty($respuestas)) {
            throw new Exception("No se recibieron respuestas.");
        }

        $sql_respuesta = "INSERT INTO enc_respuesta (idusuario, idencuesta, fecha) VALUES (:idusuario, :idencuesta, NOW())";
        $stmt_respuesta = $pdo->prepare($sql_respuesta);
        $stmt_respuesta->execute([
            'idusuario' => $idusuario,
            'idencuesta' => $idencuesta
        ]);
        $idrespuestas = $pdo->lastInsertId(); 

        foreach ($respuestas as $idpregunta => $respuesta) {
            
            if (is_array($respuesta)) {
                foreach ($respuesta as $idopcion) {
                    $sql_opcion = "INSERT INTO enc_respuestaopcion (idopciones, idrespuestas, idpregunta) 
                                   VALUES (:idopciones, :idrespuestas, :idpregunta)";
                    $stmt_opcion = $pdo->prepare($sql_opcion);
                    $stmt_opcion->execute([
                        'idopciones' => $idopcion,
                        'idrespuestas' => $idrespuestas,
                        'idpregunta' => $idpregunta
                    ]);
                }
            } else {
                $sql_texto = "INSERT INTO enc_respuestatexo (respuesta, idrespuestas, idpregunta) 
                              VALUES (:respuesta, :idrespuestas, :idpregunta)";
                $stmt_texto = $pdo->prepare($sql_texto);
                $stmt_texto->execute([
                    'respuesta' => $respuesta,
                    'idrespuestas' => $idrespuestas,
                    'idpregunta' => $idpregunta
                ]);
            }
        }

        $pdo->commit();
        echo json_encode(["status" => "success", "message" => "Respuestas guardadas correctamente."]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al guardar respuestas: " . $e->getMessage()]);
    }
}
?>
