<?php
require 'config/database.php';
session_start();



/* Para tomar el url actual con la direccion IP del servidor 
$url_actual = $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
*/

/* Se toma el url con el host "localhost" */
$url_actual = "http://localhost$_SERVER[REQUEST_URI]";
/* Se toma todo el texto que esta al final del url, en
este caso solo es el numero id de la encuesta */
$id_url = parse_url($url_actual, PHP_URL_QUERY);
$idEncuesta = $id_url;

if (isset($_GET['id'])) {
    $idEncuesta = $_GET['id'];
    // Aquí puedes usar $idEncuesta para cargar la encuesta correspondiente desde la base de datos
} else {
    echo "No se ha seleccionado ninguna encuesta.";
    exit;
}

$encuesta = [];
$preguntas = [];

$sql = "SELECT * FROM enc_encuestasm WHERE idencuesta = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $idEncuesta]);
$encuesta = $stmt->fetch(PDO::FETCH_ASSOC);

$sql_preguntas = "SELECT * FROM enc_pregunta WHERE idencuesta = :id";
$stmt_preguntas = $pdo->prepare($sql_preguntas);
$stmt_preguntas->execute(['id' => $idEncuesta]);
$preguntas = $stmt_preguntas->fetchAll(PDO::FETCH_ASSOC);

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
                <form action="guardar_respuestas.php" method="POST">
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
            </div>
        </section>
    </section>
</body>
</html>
