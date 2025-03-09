<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];  

    // Preguntas como arrays
    $preguntas = $_POST['preguntas'] ?? [];

    try {
        $pdo->beginTransaction();

        // Insertar la encuesta en la tabla de encuestas
        $sql = "INSERT INTO encuestas (titulo, descripcion, fecha) VALUES (:titulo, :descripcion, STR_TO_DATE(:fecha, '%d/%m/%Y'))"; // Convertir la fecha al formato adecuado
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['titulo' => $titulo, 'descripcion' => $descripcion, 'fecha' => $fecha]);
        $encuesta_id = $pdo->lastInsertId(); 

        // Insertar las preguntas
        foreach ($preguntas as $index => $pregunta) {
            $sql_pregunta = "INSERT INTO preguntas (encuesta_id, pregunta) VALUES (:encuesta_id, :pregunta)";
            $stmt_pregunta = $pdo->prepare($sql_pregunta);
            $stmt_pregunta->execute([
                'encuesta_id' => $encuesta_id,
                'pregunta' => $pregunta,
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
            <div class="column">
                <label style="margin-top: 4px;">Título:</label>
                <div class="input-group">
                <input type="text" name="titulo" class="form-control" required>
                </div>
            </div>
            <div class="column">
                <label style="margin-top: 4px;">Descripción:</label>
                <div class="input-group">
                <textarea name="descripcion" class="form-control" required></textarea>
                </div>
            </div>
            <div class="column">
                <label style="margin-top: 4px;">Fecha:</label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" data-plugin-datepicker="" class="form-control" data-input-mask="31/12/9999" placeholder="DD/MM/AAAA" name="fecha" id="fecha" readonly="readonly">
                </div>
            </div>
        </div>


                <h4 class="mt-4">Preguntas</h4>
                <div id="panelPreguntasContainer">
                   
                </div>

                <button type="button" class="btn btn-success mt-3" onclick="agregarPreguntaPanel()">Agregar Pregunta <i class="fa fa-plus"></i></button>

                <div class="row mt-4">
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary" onclick="abrirModalGuardar()">Guardar Encuesta <i class="fa fa-save"></i></button>
                        <a href="router.php?url=encuestas" class="btn btn-danger">Cancelar <i class="fa fa-ban"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</section>

<script>
    let preguntaCont = 1;

    $('#fecha').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        locale: "es-ES"
    });
    let fecha = new Date();
    var dia = ("0" + fecha.getDate()).slice(-2);
    var mes = ("0" + (fecha.getMonth() + 1)).slice(-2);
    var año = fecha.getFullYear();
    var fechaActual = dia + "/" + mes + "/" + año;
    var fechaFinal = dia + "/" + mes + "/" + año;
    $('#fecha').datepicker("setDate", fechaFinal);


    
    function agregarPreguntaPanel() {
    const container = document.getElementById('panelPreguntasContainer');
    const index = container.children.length + 1;

    const preguntaDiv = document.createElement('div');
    preguntaDiv.classList.add('form-group', 'mt-3');
    preguntaDiv.innerHTML = `
        <label>Pregunta ${index}:</label>
        <input type="text" name="preguntas[]" class="form-control" required>
        
        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentNode.remove()">Eliminar</button>
        
        <!-- Tipo de pregunta -->
        <div class="form-group col-md-5 mt-2">
            <label>Tipo de pregunta:</label>
            <select class="form-control" name="preguntaTipo${preguntaCont}" onchange="cambiarTipoPregunta(${preguntaCont}, this.value)" autocomplete="off">
                <option value="texto">Texto</option>
                <option value="texto_abierto">Texto abierto</option>
                <option value="opcion_unica">Opción única</option>
                <option value="opcion_multiple">Opción múltiple</option>
            </select>
        </div>

        <!-- Pregunta requerida -->
        <div class="form-group col-md-3 mt-2">
            <label>
                Requerida  
                <input type="checkbox" name="preguntaRequerida${preguntaCont}" value="1">
            </label>
        </div>
    `;
    
    container.appendChild(preguntaDiv);
    preguntaCont++; 
}

    function abrirModalGuardar() {

    }
</script>

</body>
</html>
