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

// Array que se usara para mostrar todos los resultados
$tabla = [];

// Consulta para obtener el numero de respuesta
$respuesta = "SELECT * FROM enc_respuesta  WHERE idencuesta=$idEncuesta ORDER BY idrespuestas ASC";


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
        $opciones_total = "";
        $opcion = "";
        $total_respuesta = "";
        // Primera fila de la columna, en ella estaran los nombres de las columnas
        $tabla[0][] = "Fecha";
        $tabla[0][] = "Usuario";
     
        //Para contar el numero de preguntas por encuesta
        $contador = 0;
        if ($result = $mysqli->query($respuesta)) {
            while ($row = $result->fetch_assoc()) {
                $contador = $contador + 1;
            }
        }

        // Consulta para obtener las preguntas de la encuesta
        $preguntas = "SELECT * FROM enc_pregunta WHERE idencuesta = $idEncuesta ORDER BY idencuesta ASC";

        $contador2 = 0;
        // Agregar las preguntas en la primera fila
        if ($result = $mysqli->query($preguntas)) {
            while ($row = $result->fetch_assoc()) {
                $pregunta = $row["textopregunta"];
                $tabla[0][] = $pregunta;
                // Se crea un arreglo para almacenar el numero de pregunta y su tipo dependiendo del numero de encuesta
                $preguntas_arreglo[$contador2][] = $row['idpregunta'];
                $preguntas_arreglo[$contador2][] = $row['idtipopregunta'];
                $contador2 += 1;
            }
        }

        // Se agrega al arreglo la fecha y el idusuario para cada respuesta
        $contador = 1;
        if ($result = $mysqli->query($respuesta)) {
            while ($row = $result->fetch_assoc()) {
                $fecha = $row["fecha"];
                $usuario = $row["idusuario"];
                $numero_respuesta = $row["idrespuestas"];
                $tabla[][] = $fecha;
                $tabla[$contador][] = $usuario;
                
                // $tabla[$contador][] = "respuesta = ".$numero_respuesta;  

                // Tomar respuestas tipo texto
                $respuestas_texto = "SELECT * FROM enc_respuestatexto WHERE idrespuestas=$numero_respuesta";


                foreach($preguntas_arreglo as $p){  
                    if ($p[1] == 1 or $p[1] == 2){
                        // Tomar respuestas tipo texto
                        $respuestas_texto = "SELECT * FROM enc_respuestatexto WHERE idrespuestas=$numero_respuesta AND idpregunta=$p[0]";
                        if ($result2 = $mysqli->query($respuestas_texto)){
                            while($row = $result2->fetch_assoc()){
                                $tabla[$contador][] = $row["respuesta"];
                            }
                        }
                    }  elseif ($p[1] == 3 or $p[1] == 4) {
                        // Tomar respuestas tipo opcion
                        $respuestas_texto = "SELECT * FROM enc_respuestaopcion WHERE idrespuestas=$numero_respuesta AND idpregunta=$p[0]";
                        if ($result3 = $mysqli->query($respuestas_texto)){
                            while($row = $result3->fetch_assoc()){
                                $opcion_num = $row["idopciones"];
                                // Traducir las respuestas tipo opcione de su id o numero a texto
                                $opcion_texto = "SELECT * FROM enc_opcion WHERE idopciones=$opcion_num";
                                if ($result4 = $mysqli->query($opcion_texto)){
                                    while($row = $result4->fetch_assoc()){
                                        $opcion = $row["opcion"]." ";
                                    }
                                }
                                $opciones_total = $opciones_total.$opcion;
                            }
                            $tabla[$contador][] = $opciones_total;
                            $opciones_total = "";
                        }
                    } else {
                        echo "ERROR";
                    }
                }

                $contador += 1;
            }
            $result->free();
        }

    

        ?>


        
        <?php
        // Funcion para borrar todos los valores vacios en el arreglo
/*
            $tabla2 = array_map('array_filter', $tabla);
            $tabla2 = array_filter($tabla2);
            $tabla2 = array_values($tabla2);
            $tabla2 = array_map('array_values', $tabla2);
  */
            ?>

   
        <?php 
        // Solo para pruebas
        //echo $contador;
        echo '<pre>'; print_r($tabla);echo '</pre>';
        //echo '<pre>'; print_r($tabla2);echo '</pre>';
        ?>

        <?php
        // Para descargar el archivo excel (es automatico, se guarda en la carpeta encuestas)
        require __DIR__ . '/vendor/autoload.php';
        $xlsx = Shuchkin\SimpleXLSXGen::fromArray( $tabla );
        $xlsx->saveAs($nombre.'.xlsx'); 
        ?>

    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>


</body>
</html>
