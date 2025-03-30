<?php
session_start();
require_once __DIR__ . '/config/database.php'; 

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

error_log(print_r($_POST, true)); 

if (!isset($_POST["idEncuesta"])) {
    echo json_encode(["status" => "error", "message" => "Falta el ID de la encuesta"]);
    exit;
}

$idEncuesta = $_POST["idEncuesta"];
$token = bin2hex(random_bytes(8)); 
$expira = date("Y-m-d H:i:s", strtotime("+1 day")); // expira en 1 día

$mysqli = new mysqli("localhost", "root", "", "db_encuestas");

if ($mysqli->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error de conexión"]));
}

// guardar el token en la BD
$query = "INSERT INTO enc_tokens (idencuesta, token, expira, utilizado) VALUES (?, ?, ?, 0)";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("iss", $idEncuesta, $token, $expira);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "url" => "http://localhost/encuestas/responder.php?token=$token"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al generar el token"]);
}

$stmt->close();
$mysqli->close();

?>
