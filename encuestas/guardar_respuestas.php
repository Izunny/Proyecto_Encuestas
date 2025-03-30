<?php
require 'config/database.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["status" => "error", "message" => "Error: Usuario no autenticado"]);
    exit();
}

$token = $_SESSION['encuesta_token'];

// Primero validar nuevamente el token
$sql_token = "SELECT idEncuesta FROM enc_tokens WHERE token = :token AND expira > NOW() LIMIT 1";
$stmt_token = $pdo->prepare($sql_token);
$stmt_token->execute(['token' => $token]);

if ($stmt_token->rowCount() == 0) {
    echo json_encode(['status' => 'error', 'message' => 'El token ha expirado o no es válido']);
    exit;
}

$row = $stmt_token->fetch(PDO::FETCH_ASSOC);
$idEncuesta = $row['idEncuesta'];

// Verificar si la petición es POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit();
}

try {
    $pdo->beginTransaction();
    
    $idusuario = $_SESSION['idusuario'];
    $idencuesta = $_POST['idencuesta'] ?? null;
    $respuestas = $_POST['respuestas'] ?? [];

    // Validar que se recibieron datos válidos
    if (!$idencuesta || empty($respuestas)) {
        throw new Exception("No se recibieron respuestas válidas.");
    }

    // Insertar en la tabla enc_respuesta
    $sql_respuesta = "INSERT INTO enc_respuesta (idusuario, idencuesta, fecha) VALUES (:idusuario, :idencuesta, NOW())";
    $stmt_respuesta = $pdo->prepare($sql_respuesta);
    $stmt_respuesta->execute([
        'idusuario' => $idusuario,
        'idencuesta' => $idencuesta
    ]);
    $idrespuestas = $pdo->lastInsertId(); // Obtener el ID de la respuesta insertada

    // Procesar cada pregunta y su respuesta
    foreach ($respuestas as $idpregunta => $respuesta) {
        if (is_array($respuesta)) {
            // Opción múltiple
            foreach ($respuesta as $idopcion) {
                if (!is_numeric($idopcion)) continue; // Validar que sea un número
                
                $sql_opcion = "INSERT INTO enc_respuestaopcion (idopciones, idrespuestas, idpregunta) 
                               VALUES (:idopciones, :idrespuestas, :idpregunta)";
                $stmt_opcion = $pdo->prepare($sql_opcion);
                $stmt_opcion->execute([
                    'idopciones' => $idopcion,
                    'idrespuestas' => $idrespuestas,
                    'idpregunta' => $idpregunta
                ]);
            }
        } elseif (is_numeric($respuesta)) {
            // Opción única
            $sql_opcion = "INSERT INTO enc_respuestaopcion (idopciones, idrespuestas, idpregunta) 
                           VALUES (:idopciones, :idrespuestas, :idpregunta)";
            $stmt_opcion = $pdo->prepare($sql_opcion);
            $stmt_opcion->execute([
                'idopciones' => $respuesta,
                'idrespuestas' => $idrespuestas,
                'idpregunta' => $idpregunta
            ]);
        } elseif (!empty(trim($respuesta))) {
            // Pregunta de texto
            $sql_texto = "INSERT INTO enc_respuestatexto (respuesta, idrespuestas, idpregunta) 
                          VALUES (:respuesta, :idrespuestas, :idpregunta)";
            $stmt_texto = $pdo->prepare($sql_texto);
            $stmt_texto->execute([
                'respuesta' => trim($respuesta),
                'idrespuestas' => $idrespuestas,
                'idpregunta' => $idpregunta
            ]);
        }
    }
    $sql_delete_token = "DELETE FROM enc_tokens WHERE token = :token";
    $stmt_delete_token = $pdo->prepare($sql_delete_token);
    $stmt_delete_token->execute(['token' => $token]);
    
    $pdo->commit();
    echo json_encode(["status" => "success", "message" => "Respuestas guardadas correctamente."]);

    unset($_SESSION['encuesta_token']);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => "Error al guardar respuestas: " . $e->getMessage()]);
}
?>

