<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $estado = $_POST['estado'];
  

    // Preguntas como arrays
    $preguntas = $_POST['preguntas'] ?? [];

    try {
        $pdo->beginTransaction();

        // Insertar la encuesta en la tabla de encuestas
        $sql = "INSERT INTO encuestas (titulo, descripcion, fecha, estado, obra_id, departamento_id) 
                VALUES (:titulo, :descripcion, STR_TO_DATE(:fecha, '%d/%m/%Y'), :estado, :obra_id, :departamento_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'fecha' => $fecha,
            'estado' => $estado,
            
        ]);
        $encuesta_id = $pdo->lastInsertId();

        // Insertar las preguntas
        foreach ($preguntas as $index => $pregunta) {
            $sql_pregunta = "INSERT INTO preguntas (encuesta_id, pregunta, tipo, requerida) 
                             VALUES (:encuesta_id, :pregunta, :tipo, :requerida)";
            $stmt_pregunta = $pdo->prepare($sql_pregunta);
            $stmt_pregunta->execute([
                'encuesta_id' => $encuesta_id,
                'pregunta' => $pregunta['titulo'],
                'tipo' => $pregunta['tipo'],
                'requerida' => $pregunta['requerida']
            ]);

            $pregunta_id = $pdo->lastInsertId();

            // Insertar opciones si el tipo de pregunta lo requiere
            if (in_array($pregunta['tipo'], ['opcion_unica', 'opcion_multiple'])) {
                foreach ($pregunta['opciones'] as $opcion) {
                    $sql_opcion = "INSERT INTO opciones (pregunta_id, texto) VALUES (:pregunta_id, :texto)";
                    $stmt_opcion = $pdo->prepare($sql_opcion);
                    $stmt_opcion->execute([
                        'pregunta_id' => $pregunta_id,
                        'texto' => $opcion
                    ]);
                }
            }
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
    <link rel="stylesheet" href="<?php echo '/encuestas/assets/css/style.css'; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>
<body>
    <!-- Encabezado -->
    <?php include dirname(__DIR__) . "/templates/header.php"; ?>


    
    <section class="container mt-5">
    <section class="card">
        <header class="card-header bg-primary text-white">
            <h2 class="card-title">Agregar Encuesta</h2>
        </header>
        <div class="card-body">
            <form action="router.php?url=encuestas/agregar" method="POST">
            
                <div class="row">
                    <div class="form-group col-12 col-md-3">
                        <label>Título:</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label>Descripción:</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="form-group col-12 col-md-2">
                        <label>Estado:</label>
                        <select class="form-control" name="estado">
                            <option value="S">Activo</option>
                            <option value="N">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-2">
                        <label>Fecha:</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                            </span>
                            <input type="text" data-plugin-datepicker class="form-control" data-input-mask="31/12/9999" placeholder="DD/MM/AAAA" name="fecha" id="fecha" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                <h4 class="mt-2">Preguntas</h4>
                <div id="panelPreguntasContainer"></div>
                </div>
                
                <button type="button" class="btn btn-success mt-3" onclick="agregarPreguntaPanel()">Agregar Pregunta <i class="fa fa-plus"></i></button>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar Encuesta <i class="fa fa-save"></i></button>
                        <button type="button" class="btn btn-default" onclick="Cancelar(this)">Cancelar <i class="fa fa-ban"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>
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
        $('#fecha').datepicker("setDate", fechaActual);

        function agregarPreguntaPanel() {
            const container = document.getElementById('panelPreguntasContainer');
            const index = container.children.length + 1;

            const preguntaDiv = document.createElement('div');
            preguntaDiv.classList.add('form-group', 'mt-2');
            preguntaDiv.innerHTML = `
                <label>Pregunta ${index}:</label>
                
                <div class="row">
                <input type="text" name="preguntas[${index}][titulo]" class="form-control" required>
                
                <div class="form-group col-md-10 mt-2">
                    <label>Tipo de pregunta:</label>
                    <select class="form-control" name="preguntas[${index}][tipo]" onchange="cambiarTipoPregunta(${index}, this.value)">
                        <option value="texto">Texto</option>
                        <option value="texto_abierto">Texto abierto</option>
                        <option value="opcion_unica">Opción única</option>
                        <option value="opcion_multiple">Opción múltiple</option>
                    </select>
                </div>

                <div class="form-group col-md-10 mt-2">
                    <label>
                        Requerida  
                        <input type="checkbox" name="preguntas[${index}][requerida]" value="1">
                    </label>
                </div>
            </div>
                <div id="opcionesPregunta${index}"></div>

                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentNode.remove()">Eliminar</button>
            `;

            container.appendChild(preguntaDiv);
            preguntaCont++;
        }

        function cambiarTipoPregunta(idPregunta, tipo) {
            const contenedorOpciones = document.getElementById(`opcionesPregunta${idPregunta}`);
            contenedorOpciones.innerHTML = '';

            if (tipo === 'opcion_unica' || tipo === 'opcion_multiple') {
                const opcionesHTML = `
                    <div class="form-group col-12 col-md-2">
                        <label>Opciones:</label>
                        <button type="button" class="btn btn-default" onclick="agregarOpcion(${idPregunta})">Agregar otra opción</button>
                        <div class="opcion-item mt-2">
                            <input type="text" name="preguntas[${idPregunta}][opciones][]" class="form-control" placeholder="Ingrese una opción">
                            <button type="button" class="btn btn-default" onclick="eliminarOpcion(this)">Eliminar</button>
                        </div>
                    </div>
                `;
                contenedorOpciones.innerHTML = opcionesHTML;
            }
        }

        function agregarOpcion(idPregunta) {
            const contenedorOpciones = document.getElementById(`opcionesPregunta${idPregunta}`);
            const nuevaOpcion = document.createElement('div');
            nuevaOpcion.classList.add('opcion-item', 'mt-2');
            nuevaOpcion.innerHTML = `
                <input type="text" name="preguntas[${idPregunta}][opciones][]" class="form-control" placeholder="Ingrese una opción">
                <button type="button" class="btn btn-default" onclick="eliminarOpcion(this)">Eliminar</button>
            `;
            contenedorOpciones.appendChild(nuevaOpcion);
        }

        function eliminarOpcion(element) {
            element.parentElement.remove();
        }
    </script>
</body>
</html>

