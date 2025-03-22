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

// Consulta para obtener las encuestas del usuario
$query = "SELECT * FROM enc_encuestasm INNER JOIN usuarios ON enc_encuestasm.idusuario=usuarios.idusuario ORDER BY idencuesta ASC";
?>

<!DOCTYPE html>
<html lang="es">
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
            <h3>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h3>
        </div>

        <div class="wrap-welcome">
            <h3>Encuestas activas</h3>

            <nav>
                <ul class="nav-menu">
                    <li><a href="#" id="verResultados">Ver Resultados</a></li>
                    <li><a href="#" id="responderEncuesta">Compartir encuesta</a></li>
                    <li><a href="#" id="editarEncuesta">Editar Encuesta</a></li>
                </ul>
            </nav>
                
            <form id="formSeleccionEncuesta">
                <table class="table-welcome">
                    <tr>
                        <th>Seleccionar</th>
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
                                    <td><input type='radio' name='seleccionEncuesta' value='$idEncuesta' onclick='actualizarEnlaces($idEncuesta)'></td>
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
        </div>

    </div>
    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/encuestas/assets/js/alertas.js"></script>
    <script>
        function actualizarEnlaces(idEncuesta) {
            document.getElementById("verResultados").href = "resultados.php?id=" + idEncuesta;
            document.getElementById("responderEncuesta").href = "responder.php?id=" + idEncuesta;
            document.getElementById("editarEncuesta").href = "editar.php?id=" + idEncuesta;
        }

        function actualizarEnlaces(idEncuesta) {
        document.getElementById("verResultados").href = "resultados.php?id=" + idEncuesta;
        document.getElementById("responderEncuesta").href = "responder.php?id=" + idEncuesta;
        document.getElementById("editarEncuesta").href = "editar.php?id=" + idEncuesta;
    }

    document.getElementById("verResultados").addEventListener("click", function(event) {
        const seleccionado = document.querySelector("input[name='seleccionEncuesta']:checked");
        if (!seleccionado) {
            event.preventDefault(); // Evitar que el enlace funcione
            mostrarAlerta("Error", "Por favor, selecciona una encuesta para ver los resultados.", "error");
        }
    });

    document.getElementById("editarEncuesta").addEventListener("click", function(event) {
        const seleccionado = document.querySelector("input[name='seleccionEncuesta']:checked");
        if (!seleccionado) {
            event.preventDefault(); // Evitar que el enlace funcione
            mostrarAlerta("Error", "Por favor, selecciona una encuesta para editarla.", "error");
        }
    });

    document.getElementById("responderEncuesta").addEventListener("click", function(event) {
        const seleccionado = document.querySelector("input[name='seleccionEncuesta']:checked");
        if (!seleccionado) {
            event.preventDefault(); // Evitar que el enlace funcione
            mostrarAlerta("Error", "Por favor, selecciona una encuesta para responderla.", "error");
        }
    });

    </script>

</body>
</html>
