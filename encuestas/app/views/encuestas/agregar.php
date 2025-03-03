<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    
    // Preguntas y respuestas como arrays
    $preguntas = $_POST['preguntas'] ?? [];
    $respuestas_a = $_POST['respuestas_a'] ?? [];
    $respuestas_b = $_POST['respuestas_b'] ?? [];

    try {
        $pdo->beginTransaction();

        // Insertar la encuesta en la tabla de encuestas
        $sql = "INSERT INTO encuestas (titulo, descripcion) VALUES (:titulo, :descripcion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['titulo' => $titulo, 'descripcion' => $descripcion]);
        $encuesta_id = $pdo->lastInsertId();  // Obtener el ID de la encuesta insertada

        // Insertar las preguntas y respuestas asociadas con la encuesta
        foreach ($preguntas as $index => $pregunta) {
            $sql_pregunta = "INSERT INTO preguntas (encuesta_id, pregunta, respuesta_a, respuesta_b) 
                             VALUES (:encuesta_id, :pregunta, :respuesta_a, :respuesta_b)";
            $stmt_pregunta = $pdo->prepare($sql_pregunta);
            $stmt_pregunta->execute([
                'encuesta_id' => $encuesta_id,
                'pregunta' => $pregunta,
                'respuesta_a' => $respuestas_a[$index] ?? '',
                'respuesta_b' => $respuestas_b[$index] ?? ''
            ]);
        }

        // Confirmar la transacción
        $pdo->commit();
        echo "<p>Encuesta agregada correctamente.</p>";

    } catch (Exception $e) {
        // En caso de error, hacer rollback
        $pdo->rollBack();
        echo "<p>Error al agregar la encuesta: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Encuesta</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <!-- Encabezado -->
    <header class="header-container">
        <div class="header_one">
            <a href="/"> <img class="logo" src="./imagenes/logo4.webp" alt="logo"></a>    
            <h2 class="title_page">Encuestas Dinámicas</h2>
        </div>
        <div class="header_two">
            <a href="/logout"><button class="index_button_one">Cerrar sesión</button></a>
        </div>
    </header>

    <!-- Contenido principal -->
    <section class="container mt-5">
    <section class="card">
        <header class="card-header bg-primary text-white">
            <h2 class="card-title">Agregar Encuesta</h2>
        </header>
        <div class="card-body">
            <form action="router.php?url=encuestas/agregar" method="POST">
                <div class="row">
                    <div class="mt-4">
                        <label>Título:</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="mt-4">
                        <label>Descripción:</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                </div>

                <h4 class="mt-4">Preguntas</h4>
                <div id="panelPreguntasContainer">
                    <!-- Preguntas dinámicas se agregarán aquí -->
                </div>

                <button type="button" class="btn btn-success mt-3" onclick="agregarPreguntaPanel()">Agregar Pregunta <i class="fa fa-plus"></i></button>

                <div class="row mt-4">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary">Guardar Encuesta <i class="fa fa-save"></i></button>
                        <a href="router.php?url=encuestas" class="btn btn-danger">Cancelar <i class="fa fa-ban"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</section>

<script>
    function agregarPreguntaPanel() {
        const container = document.getElementById('panelPreguntasContainer');
        const index = container.children.length + 1;

        const preguntaDiv = document.createElement('div');
        preguntaDiv.classList.add('form-group', 'mt-3');
        preguntaDiv.innerHTML = `
            <label>Pregunta ${index}:</label>
            <input type="text" name="preguntas[]" class="form-control" required>
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentNode.remove()">Eliminar</button>
        `;
        container.appendChild(preguntaDiv);
    }
</script>

</body>
</html>
