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

$idUsuario = $_SESSION["idusuario"]; 

// Filtrar 
$query = "SELECT * FROM enc_encuestasm 
          INNER JOIN usuarios ON enc_encuestasm.idusuario = usuarios.idusuario 
          WHERE enc_encuestasm.idusuario = ? 
          ORDER BY idencuesta ASC";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . "/includes/header.php"; ?>

    <div class="items-welcome">
    
        <div class="row">
        <div class="form-group col-12 col-md-9">
        <div class="wrap-welcome">
            <h3>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h3>
        </div>
        </div>
            <div class="form-group col-12 col-md-2">
                <input type="text" id="buscarEncuesta" class="form-control" placeholder="Buscar encuesta...">
            </div>
        </div>
    </div>
        <div class="wrap-welcome">
            <nav>
                <ul class="nav-menu">
                    <li><a href="#" id="verResultados">Ver Resultados</a></li>
                    <li><a href="#" id="responderEncuesta" class="disabled">Compartir Encuesta</a></li>
                    <li><a href="#" id="editarEncuesta">Editar Encuesta</a></li>
                    <li><a href="#" id="eliminarEncuesta">Eliminar Encuesta</a></li>
                </ul>
            </nav>

            <form id="formSeleccionEncuesta">
                <table class="table-welcome">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Autor</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Descargar</th>
                        </tr>
                    </thead>
                    <tbody id="tablaEncuestas">
    <?php
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $idEncuesta = $row["idencuesta"];
            $nombre = $row["nombre"];
            $descripcion = $row["descripcion"];
            $autor = $row["nombreU"];
            $fecha = $row["fecha"];
            $activo = $row["activo"];

            echo "<tr>
                    <td>
                        <input type='radio' name='seleccionEncuesta' 
                               value='$idEncuesta' 
                               data-estado='$activo' 
                               onclick='actualizarEnlaces($idEncuesta)'>
                    </td>
                    <td>$nombre</td>
                    <td>$descripcion</td>
                    <td>$autor</td>
                    <td>$fecha</td>
                    <td>
                        <button class='btn btn-sm " . ($activo == 'S' ? 'btn-success' : 'btn-danger') . "' 
                                onclick='cambiarEstado($idEncuesta, \"" . ($activo == 'S' ? 'N' : 'S') . "\")'>
                            " . ($activo == 'S' ? 'Activo' : 'Inactivo') . "
                        </button>
                    </td>
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
</tbody>

                </table>
            </form>
        </div>
    </div>

    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/encuestas/assets/js/alertas.js"></script>

    <script>
        function actualizarEnlaces(idEncuesta) {
            const seleccionado = document.querySelector("input[name='seleccionEncuesta']:checked");
            if (!seleccionado) return;

            const estadoEncuesta = seleccionado.getAttribute("data-estado");
            const compartirBtn = document.getElementById("responderEncuesta");

            document.getElementById("verResultados").href = "resultados.php?id=" + idEncuesta;
            document.getElementById("editarEncuesta").href = "editar.php?id=" + idEncuesta;

            if (estadoEncuesta === "N") {
                compartirBtn.classList.add("disabled");
                compartirBtn.removeAttribute("href");
            } else {
                compartirBtn.classList.remove("disabled");
                compartirBtn.href = "responder.php?id=" + idEncuesta;
            }
        }

        // filtro de busqueda
        document.getElementById("buscarEncuesta").addEventListener("input", function() {
            const filtro = this.value.toLowerCase();
            document.querySelectorAll("#tablaEncuestas tr").forEach(row => {
                const texto = row.innerText.toLowerCase();
                row.style.display = texto.includes(filtro) ? "" : "none";
            });
        });


        document.getElementById("verResultados").addEventListener("click", function(event) {
            if (!document.querySelector("input[name='seleccionEncuesta']:checked")) {
                event.preventDefault();
                mostrarAlerta("Error", "Por favor, selecciona una encuesta para ver los resultados.", "error");
            }
        });

        document.getElementById("editarEncuesta").addEventListener("click", function(event) {
            if (!document.querySelector("input[name='seleccionEncuesta']:checked")) {
                event.preventDefault();
                mostrarAlerta("Error", "Por favor, selecciona una encuesta para editarla.", "error");
            }
        });

        document.getElementById("responderEncuesta").addEventListener("click", function(event) {
            event.preventDefault();
            
            const seleccionado = document.querySelector("input[name='seleccionEncuesta']:checked");
            if (!seleccionado) {
                mostrarAlerta("Error", "Por favor, selecciona una encuesta.", "error");
                return;
            }

            fetch("generar_token.php", {
                method: "POST",
                body: new URLSearchParams({ idEncuesta: seleccionado.value }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    navigator.clipboard.writeText(data.url).then(() => {
                        mostrarAlerta("Éxito", "El enlace ha sido copiado al portapapeles.", "success");
                    });
                } else {
                    mostrarAlerta("Error", data.message, "error");
                }
            })
            .catch(error => console.error("Error:", error));
        });

        document.getElementById("eliminarEncuesta").addEventListener("click", function(event) {
            const seleccionado = document.querySelector("input[name='seleccionEncuesta']:checked");

            if (!seleccionado) {
                mostrarAlerta("Error", "Por favor, selecciona una encuesta para eliminar.", "error");
                return;
            }

            const idEncuesta = seleccionado.value;

            if (!confirm("¿Estás seguro de que quieres eliminar esta encuesta? Esta acción no se puede deshacer y las respuestas serán eliminadas también.")) {
                return;
            }

            fetch("eliminar_encuesta.php", {
                method: "POST",
                body: new URLSearchParams({ idEncuesta: idEncuesta }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            })
            .then(response => response.json())
            .then(data => {
                mostrarAlerta(
                    data.status === "success" ? "¡Éxito!" : "Error",
                    data.message,
                    data.status,
                    data.status === "success" ? "welcome.php" : null
                );
            })
            .catch(error => {
                console.error("Error:", error);
                mostrarAlerta("Error", "Hubo un problema al eliminar la encuesta.", "error");
            });
        });

        function cambiarEstado(idEncuesta, nuevoEstado) {
            fetch("cambiar_estado.php", {
                method: "POST",
                body: new URLSearchParams({ idEncuesta: idEncuesta, estado: nuevoEstado }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    location.reload(); // Recarga la página para reflejar cambios
                } else {
                    alert("Error al actualizar estado: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        }


    </script>
</body>
</html>
