<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$username = "root";
$password = "";
$database = "db_encuestas";
$mysqli = new mysqli("localhost", $username, $password, $database);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$idEncuesta = $_GET['id'] ?? null;

$query = "SELECT * FROM enc_encuestasm 
          INNER JOIN usuarios ON enc_encuestasm.idusuario=usuarios.idusuario 
          WHERE idencuesta = $idEncuesta 
          ORDER BY idencuesta ASC";

$tabla = [];

$respuesta = "SELECT r.*, u.nombreU 
              FROM enc_respuesta r
              JOIN usuarios u ON r.idusuario = u.idusuario
              WHERE r.idencuesta = $idEncuesta 
              ORDER BY r.idrespuestas ASC";

$opciones_total = "";
$opcion = "";
$total_respuesta = "";

$tabla[0][] = "Fecha";
$tabla[0][] = "Usuario"; 

//cuenta el numero de preguntas por encuesta
$contador = 0;
if ($result = $mysqli->query($respuesta)) {
    while ($row = $result->fetch_assoc()) {
        $contador = $contador + 1;
    }
}

//obtener las preguntas de la encuesta
$preguntas = "SELECT * FROM enc_pregunta WHERE idencuesta = $idEncuesta ORDER BY idencuesta ASC";

$contador2 = 0;
// las preguntas en la primera fila
if ($result = $mysqli->query($preguntas)) {
    while ($row = $result->fetch_assoc()) {
        $pregunta = $row["textopregunta"];
        $tabla[0][] = $pregunta;
        // arreglo para almacenar el numero de pregunta 
        $preguntas_arreglo[$contador2][] = $row['idpregunta'];
        $preguntas_arreglo[$contador2][] = $row['idtipopregunta'];
        $contador2 += 1;
    }
}

$contador = 1;
if ($result = $mysqli->query($respuesta)) {
    while ($row = $result->fetch_assoc()) {
        $fecha = $row["fecha"];
        $usuario = $row["nombreU"]; 
        $numero_respuesta = $row["idrespuestas"];
        $tabla[][] = $fecha;
        $tabla[$contador][] = $usuario;
        
        foreach($preguntas_arreglo as $p){  
            if ($p[1] == 1 or $p[1] == 2){
                // respuestas tipo texto
                $respuestas_texto = "SELECT * FROM enc_respuestatexto WHERE idrespuestas=$numero_respuesta AND idpregunta=$p[0]";
                if ($result2 = $mysqli->query($respuestas_texto)){
                    while($row = $result2->fetch_assoc()){
                        $tabla[$contador][] = $row["respuesta"];
                    }
                }
            }  elseif ($p[1] == 3 or $p[1] == 4) {
                // opcion
                $respuestas_texto = "SELECT * FROM enc_respuestaopcion WHERE idrespuestas=$numero_respuesta AND idpregunta=$p[0]";
                if ($result3 = $mysqli->query($respuestas_texto)){
                    while($row = $result3->fetch_assoc()){
                        $opcion_num = $row["idopciones"];
                        // traducir las respuestas 
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