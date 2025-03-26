<?php
session_start();
$username = "root";
$password = "";
$database = "db_encuestas";
$mysqli = new mysqli("localhost", $username, $password, $database);

if ($mysqli->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error de conexión"]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idEncuesta = $_POST["idEncuesta"];
    $nuevoEstado = $_POST["estado"];

    $query = "UPDATE enc_encuestasm SET activo = ? WHERE idencuesta = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("si", $nuevoEstado, $idEncuesta);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Estado actualizado"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar"]);
    }

    $stmt->close();
    $mysqli->close();
}
?>
