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

// Consulta para obtener numero de respuesta
$respuesta = "SELECT * FROM enc_respuesta WHERE idencuesta=$idEncuesta";

// Array que se usara para mostrar todos los resultados
$tabla = [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>

    <?php include __DIR__ . "/includes/header.php"; ?>

    <div class="items-welcome">

        <div class="wrap-welcome">
            <h3>Encuestas activas</h3>

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

    <br>
            
    </div>
        
        <?php
        // Primera fila de la columna, en ella estaran los nombres de las columnas
        $tabla[0][] = "fecha";
        $tabla[0][] = "usuario";
        
        //Para contar el numero de preguntas por encuesta
        $contador = 0;
        if ($result = $mysqli->query($respuesta)) {
            while ($row = $result->fetch_assoc()) {
                $contador = $contador + 1;
            }
        }
        ?>
        
        <?php
        $respuestas_final = "";
        $contador_preguntas = 0;
        $opc_in_array = [];
        
        if ($result = $mysqli->query($respuesta)) {
            
            while ($row = $result->fetch_assoc()) {                 
                $usuario = $row["idusuario"];
                $fecha = $row["fecha"];
                $respuestas = $row["idrespuestas"];
                $tabla[$respuestas][] = $fecha;
                $tabla[$respuestas][] = $usuario;

                $respuestas_texto = "SELECT respuesta, idpregunta FROM enc_respuestatexto WHERE idrespuestas=$respuestas";
                if ($result2 = $mysqli->query($respuestas_texto)) {
                    while ($row = $result2->fetch_assoc()) {
                        $respuesta_texto = $row["respuesta"];
                        $tabla[$respuestas][] = $respuesta_texto;
                        $pregunta = $row["idpregunta"];
                        if ($contador_preguntas < $contador){
                            $tabla[0][] = $pregunta;
                            $contador_preguntas = $contador_preguntas + 1;
                    }   
                }
                    $result2->free();
                }


                $respuestas_opcion = "SELECT * FROM enc_respuestaopcion WHERE idrespuestas=$respuestas";

                if ($result3 = $mysqli->query($respuestas_opcion)) {
                    
                    while ($row = $result3->fetch_assoc()) {

                        $pregunta = $row["idpregunta"];
                        $respuesta_opcion = $row["idopciones"];
                        $idpregunta = $row["idpregunta"];
                        $idrespuestas = $row["idrespuestas"];
                        
                        $pregunta_actual = "SELECT idopciones, idpregunta FROM enc_respuestaopcion WHERE idpregunta = $idpregunta AND idrespuestas=$idrespuestas";
                        $opciones_final = "";

                        if ($result4 = $mysqli->query($pregunta_actual)) {
                            while ($row = $result4->fetch_assoc()) {
                                $pregunta = $row["idpregunta"];
                                $respuestas_final = $row["idopciones"];
                                if (in_array($respuestas_final, $opc_in_array)){
                                    $opc_in_array = [];
                                    break;                                       
                                } else {
                                    $opc_in_array[] = $respuestas_final;
                                    $opciones_final = $opciones_final . "  " . $respuestas_final;
                                }
                            }
                                                                                                        
                            $tabla[$respuestas][] = $opciones_final;                                

                            if ($contador_preguntas < $contador){
                                $tabla[0][] = $pregunta;
                                $contador_preguntas = $contador_preguntas + 1;
                            }
                            $result4->free();
                        }
                    }
                    $result3->free();
                }    
            } 
        }
        
            $result->free();
        ?>

        <?php
        // Funcion para borrar todos los valores vacios en el arreglo

            $tabla2 = array_map('array_filter', $tabla);
            $tabla2 = array_filter($tabla2);
            $tabla2 = array_values($tabla2);
            $tabla2 = array_map('array_values', $tabla2);
        ?>

   
        <?php 
        // Solo para pruebas
        //echo $contador;
        // echo '<pre>'; print_r($tabla);echo '</pre>';
        //echo '<pre>'; print_r($tabla2);echo '</pre>';
        ?>

    <table class="table-welcome">
        <?php
            echo "<tr>";
            foreach ($tabla2[0] as $x){
                echo "<th>". $x. "</th>";
            }
            echo "</tr>";
            
            for ($x = 1; $x <= $contador_preguntas; $x++){
                echo "<tr>";
                    foreach ($tabla2[$x] as $y){
                        echo "<td>". $y . "</td>";
                    }
                echo "</tr>";
            }
            ?>
            </table>

    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>


</body>
</html>
