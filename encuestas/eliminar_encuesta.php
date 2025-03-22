<?php
require 'config/database.php';
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEncuesta = $_POST['idEncuesta'] ?? null;

    if (!$idEncuesta) {
        echo json_encode(["status" => "error", "message" => "ID de encuesta no proporcionado."]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $pdo->prepare("DELETE FROM enc_respuestaopcion WHERE idrespuestas IN (SELECT idrespuestas FROM enc_respuesta WHERE idencuesta = ?)")
            ->execute([$idEncuesta]);

        $pdo->prepare("DELETE FROM enc_respuestatexo WHERE idrespuestas IN (SELECT idrespuestas FROM enc_respuesta WHERE idencuesta = ?)")
            ->execute([$idEncuesta]);

        $pdo->prepare("DELETE FROM enc_respuesta WHERE idencuesta = ?")
            ->execute([$idEncuesta]);

        $pdo->prepare("DELETE FROM enc_opcion WHERE idpregunta IN (SELECT idpregunta FROM enc_pregunta WHERE idencuesta = ?)")
            ->execute([$idEncuesta]);

        $pdo->prepare("DELETE FROM enc_pregunta WHERE idencuesta = ?")
            ->execute([$idEncuesta]);

        $pdo->prepare("DELETE FROM enc_encuestasm WHERE idencuesta = ?")
            ->execute([$idEncuesta]);

        $pdo->commit();
        echo json_encode(["status" => "success", "message" => "Encuesta eliminada correctamente."]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al eliminar la encuesta: " . $e->getMessage()]);
    }
}
?>
