<?php
require 'config/database.php';
session_start();

$token = $_GET["token"] ?? '';

// Validar el token
$sql_token = "SELECT idEncuesta FROM enc_tokens WHERE token = :token AND expira > NOW() LIMIT 1";
$stmt_token = $pdo->prepare($sql_token);
$stmt_token->execute(['token' => $token]);

// Mostrar un error si el token no existe o ha expirado
if ($stmt_token->rowCount() == 0) {
    header("Location: token_expirado.php");
        exit;
}

$row = $stmt_token->fetch(PDO::FETCH_ASSOC);
$idEncuesta = $row ? $row['idEncuesta'] : null;


// Eliminar el token para que no pueda reutilizarse
$sql_delete_token = "DELETE FROM enc_tokens WHERE token = :token";
$stmt_delete_token = $pdo->prepare($sql_delete_token);
$stmt_delete_token->execute(['token' => $token]);

$_SESSION['redirect_url'] = "http://localhost/encuestas/responder.php?token=" . $token;

if (!$idEncuesta) {
    echo "No se ha seleccionado ninguna encuesta.";
    exit;
}

// detalles de la encuesta
$encuesta = [];
$preguntas = [];

$sql_encuesta = "SELECT * FROM enc_encuestasm WHERE idencuesta = :id";
$stmt_encuesta = $pdo->prepare($sql_encuesta);
$stmt_encuesta->execute(['id' => $idEncuesta]);
$encuesta = $stmt_encuesta->fetch(PDO::FETCH_ASSOC);

// las preguntas de la encuesta
$sql_preguntas = "SELECT * FROM enc_pregunta WHERE idencuesta = :id";
$stmt_preguntas = $pdo->prepare($sql_preguntas);
$stmt_preguntas->execute(['id' => $idEncuesta]);
$preguntas = $stmt_preguntas->fetchAll(PDO::FETCH_ASSOC);

// las opciones de cada pregunta
$nuevas_preguntas = [];
foreach ($preguntas as $pregunta) {
    $sql_opciones = "SELECT * FROM enc_opcion WHERE idpregunta = :idpregunta";
    $stmt_opciones = $pdo->prepare($sql_opciones);
    $stmt_opciones->execute(['idpregunta' => $pregunta['idpregunta']]);
    
    $opciones = $stmt_opciones->fetchAll(PDO::FETCH_ASSOC);
    $pregunta['opciones'] = in_array($pregunta['idtipopregunta'], [3, 4]) ? $opciones : [];
    
    $nuevas_preguntas[] = $pregunta;
}

$preguntas = $nuevas_preguntas;
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Encuesta</title>
    <link rel="stylesheet" href="/encuestas/assets/css/style.css">
</head>
<body>


    <div class="header-container">
        <div class="header_one">
            <a href="/encuestas/index.php">
                <img class="logo" src="/encuestas/imagenes/logo4.webp" alt="logo">
            </a>
            <h2 class="title_page">Encuestas Dinámicas</h2>
        </div>
    </div>

    <section class="container mt-5">
        <section class="card">
            <header class="card-header bg-success text-white">
                <h2 class="card-title"><?php echo $encuesta['nombre'] ?? 'Encuesta'; ?></h2>
            </header>
            <div class="card-body">

                <?php if (!isset($_SESSION['idusuario'])): ?>
                    <!-- usuario sin inicio -->
                    <div class="alert alert-warning">
                        <h4>¡Regístrate o inicia sesión para responder!</h4>
                        <p>Para participar en la encuesta, debes estar registrado.</p>
                        <a href="register.php" class="btn btn-primary">Registrarse</a>
                        <a href="login.php" class="btn btn-secondary">Iniciar sesión</a>
                    </div>
                <?php else: ?>
                    <!-- con usuario -->
                    <form id="formEncuesta">
                        <input type="hidden" name="idencuesta" value="<?php echo $idEncuesta; ?>">

                        <?php foreach ($preguntas as $pregunta): ?>
                            <div class="form-group mt-3">
                                <label><?php echo $pregunta['textopregunta']; ?></label>

                                <?php if ($pregunta['idtipopregunta'] == 1): ?>
                                    <input type="text" name="respuestas[<?php echo $pregunta['idpregunta']; ?>]" class="form-control">
                                
                                <?php elseif ($pregunta['idtipopregunta'] == 2): ?>
                                    <textarea name="respuestas[<?php echo $pregunta['idpregunta']; ?>]" class="form-control"></textarea>
                                
                                <?php elseif ($pregunta['idtipopregunta'] == 3): ?>
                                    <?php foreach ($pregunta['opciones'] as $opcion): ?>
                                        <div class="form-check">
                                            <input type="radio" name="respuestas[<?php echo $pregunta['idpregunta']; ?>]" value="<?php echo $opcion['idopciones']; ?>" class="form-check-input">
                                            <label class="form-check-label"><?php echo $opcion['opcion']; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                
                                <?php elseif ($pregunta['idtipopregunta'] == 4): ?>
                                    <?php foreach ($pregunta['opciones'] as $opcion): ?>
                                        <div class="form-check">
                                            <input type="checkbox" name="respuestas[<?php echo $pregunta['idpregunta']; ?>][]" value="<?php echo $opcion['idopciones']; ?>" class="form-check-input">
                                            <label class="form-check-label"><?php echo $opcion['opcion']; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Enviar Respuestas</button>
                            <button type="button" class="btn btn-default" onclick="window.location.href='welcome.php'">
                                Cancelar <i class="fa fa-ban"></i>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </section>
    </section>

    
<?php include __DIR__ . "/includes/modal_alerta.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/encuestas/assets/js/alertas.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const formEncuesta = document.getElementById("formEncuesta");
    if (formEncuesta) {
        formEncuesta.addEventListener("submit", function(event) {
            event.preventDefault(); 

            const formData = new FormData(this); 

            fetch("guardar_respuestas.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "error") {
                    
                    mostrarAlerta("Error", data.message, "error");
                } else {
                    // Mostrar alerta con el mensaje de éxito
                    mostrarAlerta(
                        "¡Éxito!",
                        data.message,
                        "success",
                        data.status === "success" ? "welcome.php" : null
                    );
                }
            })
            .catch(error => {
                console.error("Error:", error);
                mostrarAlerta("Error", "Ocurrió un error al procesar la encuesta", "error");
            });
        });
    }
});



function mostrarAlerta(titulo, mensaje, tipo = "error", redireccion = null) {
    const alertBox = document.getElementById("customAlert");
    const alertTitle = document.getElementById("customAlertTitle");
    const alertMessage = document.getElementById("customAlertMessage");
    const alertClose = document.getElementById("customAlertClose");

    if (!alertBox || !alertTitle || !alertMessage || !alertClose) return;

    // Configurar el contenido de la alerta
    alertTitle.innerText = titulo;
    alertMessage.innerText = mensaje;
    
    // Mostrar la alerta
    alertBox.style.display = "flex";

    // Cerrar alerta al hacer clic en el botón
    alertClose.onclick = function () {
        alertBox.style.display = "none";
        if (redireccion) {
            window.location.href = redireccion;
        }
    };
}


</script>

</body>
</html>
