<?php
require_once __DIR__ . '/config/database.php';
session_start(); // asegura que la sesión está activa para identificar al usuario

// antes de hacer cualquier cosa, verificamos que el usuario esté autenticado
if (!isset($_SESSION['idusuario'])) {
    echo json_encode(["status" => "error", "message" => "Error: Usuario no autenticado"]);
    exit(); // si no hay sesión, se corta la ejecución aquí mismo
}

// para depuración: Si algo falla con la sesión, puedes descomentar esta línea
// var_dump($_SESSION);

if ($_SERVER["REQUEST_METHOD"] == "POST") { // solo procesamos si la petición es POST
    try {
        $pdo->beginTransaction(); // iniciamos una transacción para que todo se guarde correctamente

        // obtenemos los datos del formulario y los limpiamos con `trim()` para evitar espacios extras
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $fecha = trim($_POST['fecha']);
        $estado = trim($_POST['estado']);
        $idusuario = $_SESSION['idusuario']; // este es el usuario que está creando la encuesta

        // insertamos la encuesta en la tabla `enc_encuestasm`
        $sql = "INSERT INTO enc_encuestasm (nombre, descripcion, fecha, activo, idusuario) 
                VALUES (:titulo, :descripcion, STR_TO_DATE(:fecha, '%d/%m/%Y'), :estado, :idusuario)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'fecha' => $fecha,
            'estado' => $estado,
            'idusuario' => $idusuario // guardamos correctamente el ID del usuario
        ]);
        $encuesta_id = $pdo->lastInsertId(); // guardamos el ID de la encuesta recién creada

        // mapeamos los tipos de preguntas con sus IDs en la base de datos
        $tipos_pregunta = [
            "texto" => 1,
            "texto_abierto" => 2,
            "opcion_unica" => 3,
            "opcion_multiple" => 4
        ];

        // procesamos las preguntas enviadas en el formulario
        $preguntas = $_POST['preguntas'] ?? []; // si no hay preguntas, usamos un array vacío para evitar errores
        foreach ($preguntas as $pregunta) {
            // insertamos cada pregunta en la tabla `enc_pregunta`
            $sql_pregunta = "INSERT INTO enc_pregunta (idencuesta, textopregunta, idtipopregunta, requerida) 
                             VALUES (:idencuesta, :textopregunta, :idtipopregunta, :requerida)";
            $stmt_pregunta = $pdo->prepare($sql_pregunta);
            $stmt_pregunta->execute([
                'idencuesta' => $encuesta_id,
                'textopregunta' => $pregunta['titulo'],
                'idtipopregunta' => $tipos_pregunta[$pregunta['tipo']], // convertimos el tipo de texto a su ID correspondiente
                'requerida' => isset($pregunta['requerida']) ? 1 : 0 // si no está marcada como requerida, la ponemos en 0
            ]);
            $pregunta_id = $pdo->lastInsertId(); // guardamos el ID de la pregunta recién creada

            // si la pregunta es de opción única o múltiple, guardamos sus opciones en la tabla `enc_opcion`
            if (in_array($tipos_pregunta[$pregunta['tipo']], [3, 4]) && !empty($pregunta['opciones'])) {
                foreach ($pregunta['opciones'] as $opcion) {
                    $sql_opcion = "INSERT INTO enc_opcion (idpregunta, opcion) VALUES (:idpregunta, :opcion)";
                    $stmt_opcion = $pdo->prepare($sql_opcion);
                    $stmt_opcion->execute([
                        'idpregunta' => $pregunta_id,
                        'opcion' => $opcion
                    ]);
                }
            }
        }

        // si todo salió bien, confirmamos los cambios en la base de datos
        $pdo->commit();
        echo json_encode(["status" => "success", "message" => "Encuesta guardada correctamente."]);

    } catch (Exception $e) {
        // si algo falla, hacemos un rollback para deshacer cualquier cambio parcial en la BD
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al guardar la encuesta: " . $e->getMessage()]);
    }
}
?>
