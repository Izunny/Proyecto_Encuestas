<?php
require 'config/database.php';
session_start();
// esta seccion se debe cambiar solo es de pruebas, la encuesta debe ser 
// llamada desde el welcome
if (isset($_GET['id'])) {
    $idEncuesta = $_GET['id'];
    // Aquí puedes usar $idEncuesta para cargar la encuesta correspondiente desde la base de datos
} else {
    echo "No se ha seleccionado ninguna encuesta.";
    exit;
}

$encuesta = [];
$preguntas = [];

if ($idEncuesta) {
    // Obtener los datos de la encuesta
    $sql = "SELECT * FROM enc_encuestasm WHERE idencuesta = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $idEncuesta]);
    $encuesta = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener las preguntas de la encuesta
    $sql_preguntas = "SELECT * FROM enc_pregunta WHERE idencuesta = :id";
    $stmt_preguntas = $pdo->prepare($sql_preguntas);
    $stmt_preguntas->execute(['id' => $idEncuesta]);
    $preguntas = $stmt_preguntas->fetchAll(PDO::FETCH_ASSOC);

    // Obtener las opciones de cada pregunta
    $nuevas_preguntas = [];

foreach ($preguntas as $pregunta) {
    if (!isset($pregunta['idpregunta'])) continue;

    $sql_opciones = "SELECT * FROM enc_opcion WHERE idpregunta = :idpregunta";
    $stmt_opciones = $pdo->prepare($sql_opciones);
    $stmt_opciones->execute(['idpregunta' => $pregunta['idpregunta']]);
    
    $opciones = $stmt_opciones->fetchAll(PDO::FETCH_ASSOC);
    
    // Solo agregar opciones si es opción única (3) o múltiple (4)
    if (in_array($pregunta['idtipopregunta'], ['3', '4'])) {
        $pregunta['opciones'] = $opciones ?: [];
    } else {
        $pregunta['opciones'] = [];
    }

    $nuevas_preguntas[] = $pregunta; // Guardar en nuevo array
}

$preguntas = $nuevas_preguntas; // Reemplazar el array original con el corregido


    
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $idEncuesta ? 'Editar' : 'Agregar'; ?> Encuesta</title>
    <link rel="stylesheet" href="<?php echo '/encuestas/assets/css/style.css'; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>
<body>
    <?php include __DIR__ . "/includes/header.php"; ?>

    <section class="container mt-5">
        <section class="card">
            <header class="card-header bg-primary text-white">
                <h2 class="card-title"><?php echo $idEncuesta ? 'Editar' : 'Agregar'; ?> Encuesta</h2>
            </header>
            <div class="card-body">
                <form action="guardar_encuesta.php" method="POST">
                    <input type="hidden" name="idencuesta" value="<?php echo $idEncuesta; ?>">
                    
                    <div class="row">
                        <div class="form-group col-12 col-md-4">
                            <label>Título:</label>
                            <input type="text" name="titulo" class="form-control" value="<?php echo $encuesta['nombre'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <label>Descripción:</label>
                            <textarea name="descripcion" class="form-control" required><?php echo $encuesta['descripcion'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-group col-10 col-md-1">
                            <label>Estado:</label><br>
                            <select class="form-control1" name="estado">
                                <option value="S" <?php echo ($encuesta['activo'] ?? '') == 'S' ? 'selected' : ''; ?>>Activo</option>
                                <option value="N" <?php echo ($encuesta['activo'] ?? '') == 'N' ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group col-10 col-md-1">
                            <label>Fecha:</label><br>
                            <div class="input-group">
                                <input type="text" data-plugin-datepicker class="form-control" data-input-mask="31/12/9999" placeholder="DD/MM/AAAA" name="fecha" id="fecha" value="<?php echo date('d/m/Y', strtotime($encuesta['fecha'] ?? '')); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h4 class="mt-2">Preguntas</h4>
                        <div id="panelPreguntasContainer">
                            <?php foreach ($preguntas as $index => $pregunta): ?>
                                <div class="form-group mt-2">
                                    <label>Pregunta <?php echo $index + 1; ?>:</label>
                                    <div class="row">
                                        <input type="text" name="preguntas[<?php echo $index; ?>][titulo]" class="form-control" value="<?php echo $pregunta['textopregunta']; ?>" required>
                                        
                                        <div class="form-group col-md-10 mt-2">
                                            <label>Tipo de pregunta:</label>
                                            <select class="form-control" name="preguntas[<?php echo $index; ?>][tipo]" onchange="cambiarTipoPregunta(<?php echo $index; ?>, this.value)">
                                                <option value="1" <?php echo $pregunta['idtipopregunta'] == '1' ? 'selected' : ''; ?>>Texto</option>
                                                <option value="2" <?php echo $pregunta['idtipopregunta'] == '2' ? 'selected' : ''; ?>>Texto abierto</option>
                                                <option value="3" <?php echo $pregunta['idtipopregunta'] == '3' ? 'selected' : ''; ?>>Opción única</option>
                                                <option value="4" <?php echo $pregunta['idtipopregunta'] == '4' ? 'selected' : ''; ?>>Opción múltiple</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-10 mt-2">
                                            <label>
                                                Requerida  
                                                <input type="checkbox" name="preguntas[<?php echo $index; ?>][requerida]" value="1" <?php echo $pregunta['requerida'] ? 'checked' : ''; ?>>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="opcionesPregunta<?php echo $index; ?>">
                                        <?php if (in_array($pregunta['idtipopregunta'], ['3', '4'])): ?>
                                            <?php foreach ($pregunta['opciones'] as $opcion): ?>
                                                <div class="opcion-item mt-2">
                                                    <input type="text" name="preguntas[<?php echo $index; ?>][opciones][]" class="form-control" value="<?php echo $opcion['opcion']; ?>">
                                                    <button type="button" class="btn btn-default" onclick="eliminarOpcion(this)">Eliminar</button>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentNode.remove()">Eliminar</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-success mt-3" onclick="agregarPreguntaPanel()">Agregar Pregunta <i class="fa fa-plus"></i></button>
                            <button type="submit" class="btn btn-primary">Guardar Encuesta <i class="fa fa-save"></i></button>
                            <button type="button" class="btn btn-default" onclick="window.location.href='welcome.php'">
                                Cancelar <i class="fa fa-ban"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </section>
    <?php include __DIR__ . "/includes/modal_alerta.php"; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/encuestas/assets/js/alertas.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>
    <script>
        let preguntaCont = <?php echo count($preguntas); ?>;

        $('#fecha').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            locale: "es-ES"
        });

        function agregarPreguntaPanel() {
            const container = document.getElementById('panelPreguntasContainer');
            const index = container.children.length;

            const preguntaDiv = document.createElement('div');
            preguntaDiv.classList.add('form-group', 'mt-2');
            preguntaDiv.innerHTML = `
                <label>Pregunta ${index + 1}:</label>
                <div class="row">
                    <input type="text" name="preguntas[${index}][titulo]" class="form-control" required>
                    
                    <div class="form-group col-md-10 mt-2">
                        <label>Tipo de pregunta:</label>
                        <select class="form-control" name="preguntas[${index}][tipo]" onchange="cambiarTipoPregunta(${index}, this.value)">
                            <option value="1">Texto</option>
                            <option value="2">Texto abierto</option>
                            <option value="3">Opción única</option>
                            <option value="4">Opción múltiple</option>
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

            if (tipo === '3' || tipo === '4') { // 3 = opción única, 4 = opción múltiple
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

        document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario

        const formData = new FormData(form);

        fetch("guardar_encuesta.php", {
            method: "POST",
            body: formData
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
            mostrarAlerta("Error", "Hubo un problema al enviar el formulario.", "error");
        });
    });
});
    </script>
</body>
</html>