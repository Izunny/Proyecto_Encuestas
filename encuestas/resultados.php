<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>

<?php include __DIR__ . "/includes/arreglo_resultados.php"; ?>

<div class="items-resultados">

    <div class="wrap-resultados">
        <h3>Encuestas activas</h3>
        <table class="table-resultados">
            <tr>
                <th>Titulo</th>
                <th>Descripción</th>
                <th>Autor</th>
                <th>Fecha</th>
                <th>Estado</th>
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
            </tr>
        </table>
    </div>
        

    <div class="wrap-resultados">
    <h3>Respuestas</h3>
        <table class="table-resultados">
            <?php
            $primera_fila = 0;
            // Insertar datos a la tabla
            foreach($tabla as $datos){
                echo "<tr>";
                foreach($datos as $dato){
                    if($primera_fila == 0){
                        echo "<th>".$dato."</th>";
                    } else {
                        echo "<td>".$dato."</td>";
                    }
                }
                $primera_fila += 1;
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    
    <br>

    <div class="wrap-resultados">
        <form method="post">
            <input type= "submit" class="index_button_descargar" name="descargar" value="Descargar"/>
        </form>
    </div>

</div>

    <?php
        if(array_key_exists('descargar', $_POST)) {
            require __DIR__ . '/vendor/autoload.php';
            $xlsx = Shuchkin\SimpleXLSXGen::fromArray( $tabla );
            $xlsx->saveAs($nombre.'.xlsx'); 
        }
    ?>
    <?php 
    // Solo para pruebas
    //echo '<pre>'; print_r($tabla);echo '</pre>';
    ?>

    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>
   
</body>
</html>
