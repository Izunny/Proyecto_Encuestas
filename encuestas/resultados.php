<?php
// Se inicia la sesión
session_start();

// Se verifica si el usuario ha ingresado, si no, redirecciona a login.php
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Conexión a la base de datos
$username = "root";
$password = "";
$database = "db_encuestas";
$mysqli = new mysqli("localhost", $username, $password, $database);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Obtener el ID de la encuesta desde la URL
$idEncuesta = $_GET['id'] ?? null;

// Consulta para obtener las encuestas del usuario
$query = "SELECT * FROM enc_encuestasm INNER JOIN usuarios ON enc_encuestasm.idusuario=usuarios.idusuario WHERE idencuesta = $idEncuesta ORDER BY idencuesta ASC";

// Consulta para obtener la ID de la respuesta
$idrespuesta = "SELECT idrespuesta FROM enc_respuesta WHERE idencuesta = $idEncuesta";

// Consulta para obtener las preguntas
$preguntas = "SELECT * FROM enc_pregunta WHERE idencuesta = $idEncuesta";

$respuestas = "SELECT * FROM enc_respuesta WHERE idencuesta = $idEncuesta";

$respuesta_opcion = "SELECT idopciones FROM enc_respuestaopcion INNER JOIN enc_pregunta ON enc_respuestaopcion.idpregunta=enc_pregunta.idpregunta WHERE idencuesta = $idEncuesta";

$respuesta_texto = "SELECT respuesta FROM enc_respuestatexto INNER JOIN enc_pregunta ON enc_respuestatexto.idpregunta=enc_pregunta.idpregunta WHERE idencuesta = $idEncuesta";

$opciones = "SELECT opcion FROM enc_opcion INNER JOIN enc_respuestaopcion ON enc_opcion.idpregunta=enc_respuestaopcion.idpregunta";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
      
    </style>
</head>
<body>

    <?php include __DIR__ . "/includes/header.php"; ?>

    <div class="items-welcome">

        <div class="wrap-welcome">
            <h3>Encuestas activas</h3>

            
            <form id="formSeleccionEncuesta">
                <table class="table-welcome">
                    <tr>
                        <th>Titulo</th>
                        <th>Descripción</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                    <?php
                    if ($result = $mysqli->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            $idEncuesta = $row["idencuesta"];
                            $nombre = $row["nombre"];
                            $descripcion = $row["descripcion"];
                            $autor = $row["nombreU"];
                            $fecha = $row["fecha"];
                            $activo = $row["activo"];
                            echo "<tr>
                                    <td>$nombre</td>
                                    <td>$descripcion</td>
                                    <td>$autor</td>
                                    <td>$fecha</td>
                                    <td>";
                                        echo ($activo == 'S') ? 'Activo' : 'Inactivo'; 
                            echo "</td>
                                  </tr>";
                        }
                        $result->free();
                    }
                    ?>
                </table>
            </form>

            <br>

            <form id="formSeleccionEncuesta">
                <table class="table-welcome">
                    <?php

                    /* Primera fila de las preguntas */
                    if ($result = $mysqli->query($preguntas)) {
                        echo "<tr>";
                        while ($row = $result->fetch_assoc()) {
                            $textopregunta = $row["textopregunta"];
                            echo "<th> $textopregunta </th>";
                        }
                        echo "</tr>";
                    }
                    ?>

                    <?php
                    $resultado_texto = mysqli_query($mysqli, $respuesta_texto);
                    $resultado_opcion = mysqli_query($mysqli, $respuesta_opcion);
                    $opcion = mysqli_query($mysqli, $opciones);

                    if ($results = $mysqli->query($respuestas)) {
                        while ($rows = $results->fetch_assoc()){
                            if ($result = $mysqli->query($preguntas)) {
                                echo "<tr>";
                                while ($row = $result->fetch_assoc()) {
                                    $tipo_pregunta = $row["idtipopregunta"];
                                    if ($tipo_pregunta == "1" or $tipo_pregunta == "2"){
                                        $fila = $resultado_texto->fetch_assoc();
                                        $salida = $fila['respuesta'];
                                        echo "<td>$salida</td>";
                                    } else {
                                        $fila = $resultado_opcion->fetch_assoc();
                                        $opcionR = $opcion->fetch_assoc();
                                        $salida = $fila['idopciones'];
                                        echo "<td>$salida</td>";
                                    }
                                    }
                                }
                                echo "</tr>";
                            }                       
                        }
                    ?>


                </table>
            </form>
        </div>

    </div>
    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>


</body>
</html>
