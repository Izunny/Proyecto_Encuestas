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
        body { font: 14px sans-serif; text-align: center; }
        .table-welcome { width: 80%; margin: 20px auto; border-collapse: collapse; }
        .table-welcome td, .table-welcome th { border: 1px solid black; padding: 10px; }
        .table-welcome th { background-color: #f2f2f2; }
        .nav-menu { list-style-type: none; padding: 0; }
        .nav-menu li { display: inline; margin: 10px; }
        .nav-menu a { text-decoration: none; padding: 10px 15px; background-color: #007bff; color: white; border-radius: 5px; }
        .nav-menu a:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <?php include __DIR__ . "/includes/header.php"; ?>

    <div class="items-welcome">
        <div class="wrap-welcome">
            <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
        </div>

        <div class="wrap-welcome">
            <h2>Encuestas activas</h2>
            
            <form id="formSeleccionEncuesta">
                <table class="table-welcome">
                    <tr>
                        <th>Seleccionar</th>
                        <th>Nombre de Encuesta</th>
                        <th>Descripción</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                    </tr>
                    <?php
                    if ($result = $mysqli->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            $idEncuesta = $row["idencuesta"];
                            $nombre = $row["nombre"];
                            $descripcion = $row["descripcion"];
                            $autor = $row["nombreU"];
                            $fecha = $row["fecha"];
                            echo "<tr>
                                    <td><input type='radio' name='seleccionEncuesta' value='$idEncuesta' onclick='actualizarEnlaces($idEncuesta)'></td>
                                    <td>$nombre</td>
                                    <td>$descripcion</td>
                                    <td>$autor</td>
                                    <td>$fecha</td>
                                  </tr>";
                        }
                        $result->free();
                    }
                    ?>
                </table>
            </form>
        </div>

        <!-- Menú con enlaces dinámicos -->
        <nav>
            <ul class="nav-menu">
                <li><a href="#" id="verResultados">Ver Resultados</a></li>
                <li><a href="#" id="responderEncuesta">Responder</a></li>
                <li><a href="#" id="editarEncuesta">Editar Encuesta</a></li>
                <li><a href="agregar.php">Crear Encuesta</a></li>
            </ul>
        </nav>
    </div>

    <script>
        function actualizarEnlaces(idEncuesta) {
            document.getElementById("verResultados").href = "resultados.php?id=" + idEncuesta;
            document.getElementById("responderEncuesta").href = "responder.php?id=" + idEncuesta;
            document.getElementById("editarEncuesta").href = "editar.php?id=" + idEncuesta;
        }
    </script>

</body>
</html>
