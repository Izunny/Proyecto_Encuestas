<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Encuesta</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . "/includes/arreglo_resultados.php"; ?>

<div class="items-resultados">
    <div class="wrap-resultados">
        <h3>Encuestas activas</h3>
        <table class="table-resultados">
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Autor</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Descargar</th>
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
                            <td>" . ($activo == 'S' ? 'Activo' : 'Inactivo') . "</td>
                            <td>
                                <a href='descargar.php?idEncuesta=$idEncuesta' class='btn btn-primary'>
                                    Descargar
                                </a>
                            </td>
                          </tr>";
                }
                $result->free();
            }
            ?>
        </table>
    </div>

    <div class="wrap-resultados">
        <h3>Respuestas</h3>
        <table class="table-resultados">
            <?php
            $primera_fila = 0;
            foreach($tabla as $datos){
                echo "<tr>";
                foreach($datos as $dato){
                    if($primera_fila == 0){
                        echo "<th>".htmlspecialchars($dato)."</th>";
                    } else {
                        echo "<td>".htmlspecialchars($dato)."</td>";
                    }
                }
                $primera_fila += 1;
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

<?php include __DIR__ . "/includes/modal_alerta.php"; ?>

</body>
</html>