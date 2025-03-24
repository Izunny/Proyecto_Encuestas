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

// Array que se usara para mostrar todos los resultados
$tabla = [];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
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
                    // Loop para hacer una tabla con informacion de la encuesta
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
                
        </div>

        <div class="wrap-welcome">
    
            <form id="formSeleccionEncuesta">
                    
                    <?php
                    if ($result = $mysqli->query($preguntas)) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="preguntas">';
                            $textopregunta = $row["textopregunta"];
                            echo '<div style="font-weight: bold;">';
                            echo $textopregunta;
                            echo '</div>';
                            $num_pregunta = $row["idpregunta"];
                            $tipo_pregunta = $row["idtipopregunta"];
                            $tabla[] = $textopregunta;
                            echo "<br>";
                            // Resultados de la pregunta actual si son tipo texto
                            if ($tipo_pregunta == "1" or $tipo_pregunta == "2"){
                                $resultados_texto = "SELECT respuesta FROM enc_respuestatexto WHERE idpregunta = $num_pregunta";
                                if ($result1=$mysqli->query($resultados_texto)){
                                    while ($row = $result1->fetch_assoc()){
                                        $tabla[$textopregunta][]=$row["respuesta"];
                                        $valor = $row["respuesta"];
                                        echo "$valor<br>";
                                    }
                                }
                            // Resultados de la pregunta actual si son tipo opcion
                            } else {
                                    $resultados_opcion = "SELECT idopciones FROM enc_respuestaopcion WHERE idpregunta = $num_pregunta";
                                    if ($result2=$mysqli->query($resultados_opcion)){
                                        while($row = $result2->fetch_assoc()){
                                            $tabla[$textopregunta][]=$row["idopciones"];
                                            $valor = $row["idopciones"];
                                            // Consulta para las opciones dependiendo de las respuestas
                                            $opcion_texto = "SELECT opcion FROM enc_opcion WHERE idopciones=$valor";
                                            $result3 = $mysqli->query($opcion_texto);
                                            while($valor_texto = $result3->fetch_assoc()){
                                                $valor_final=$valor_texto["opcion"];
                                            };
                                            echo "$valor_final<br>";                                            
                                        }
                                    }
                            }
                            echo '</div>';
                        }
                        $result->free();
                    }
                    ?>
                    <h1>hey</h1>                        
                    <?php
                        echo '<pre>'; print_r($tabla); echo '</pre>';
                    ?>
            </form>
        </div>
    </div>

    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>


</body>
</html>
