<?php
session_start();

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Verificar autenticación
if (!isset($_SESSION["loggedin"])) {
    header("location: login.php");
    exit;
}

// Verificar ID de encuesta
if (!isset($_GET['idEncuesta']) || empty($_GET['idEncuesta'])) {
    die("ID de encuesta no proporcionado");
}

$idEncuesta = $_GET['idEncuesta'];
$idUsuario = $_SESSION["idusuario"];

// Conexión a la base de datos
$username = "root";
$password = "";
$database = "db_encuestas";
$mysqli = new mysqli("localhost", $username, $password, $database);

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Verificar que la encuesta pertenece al usuario
$query = "SELECT idencuesta FROM enc_encuestasm WHERE idencuesta = ? AND idusuario = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $idEncuesta, $idUsuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No tienes permiso para descargar esta encuesta o no existe");
}

// Obtener información básica de la encuesta
$queryEncuesta = "SELECT nombre, descripcion, fecha FROM enc_encuestasm WHERE idencuesta = ?";
$stmtEncuesta = $mysqli->prepare($queryEncuesta);
$stmtEncuesta->bind_param("i", $idEncuesta);
$stmtEncuesta->execute();
$encuesta = $stmtEncuesta->get_result()->fetch_assoc();

// Crear un nuevo documento de Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establecer propiedades del documento
$spreadsheet->getProperties()
    ->setCreator($_SESSION["username"])
    ->setTitle("Encuesta: " . $encuesta['nombre'])
    ->setDescription("Exportación de respuestas de encuesta");

// Estilos para los encabezados
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4472C4'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
];

// Estilo para las celdas de datos
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
    'alignment' => [
        'wrapText' => true,
    ],
];

// Escribir metadatos de la encuesta
$sheet->setCellValue('A1', 'Encuesta:');
$sheet->setCellValue('B1', $encuesta['nombre']);
$sheet->setCellValue('A2', 'Descripción:');
$sheet->setCellValue('B2', $encuesta['descripcion']);
$sheet->setCellValue('A3', 'Fecha creación:');
$sheet->setCellValue('B3', $encuesta['fecha']);
$sheet->setCellValue('A4', 'Fecha exportación:');
$sheet->setCellValue('B4', date('Y-m-d H:i:s'));

// Obtener todas las preguntas de la encuesta
$queryPreguntas = "SELECT idpregunta, textopregunta, idtipopregunta 
                   FROM enc_pregunta 
                   WHERE idencuesta = ? 
                   ORDER BY idpregunta ASC";
$stmtPreguntas = $mysqli->prepare($queryPreguntas);
$stmtPreguntas->bind_param("i", $idEncuesta);
$stmtPreguntas->execute();
$preguntas = $stmtPreguntas->get_result();

// Preparar encabezados
$encabezados = ["ID Respuesta", "Usuario", "Fecha"];
$preguntasInfo = [];
$columna = 4; // Empezamos en la columna D (las primeras son A,B,C)

while ($pregunta = $preguntas->fetch_assoc()) {
    $encabezados[] = $pregunta['textopregunta'];
    $preguntasInfo[$pregunta['idpregunta']] = [
        'texto' => $pregunta['textopregunta'],
        'tipo' => $pregunta['idtipopregunta'],
        'columna' => $columna++
    ];
}

// Escribir encabezados
$sheet->fromArray($encabezados, NULL, 'A6');
$sheet->getStyle('A6:' . $sheet->getHighestColumn() . '6')->applyFromArray($headerStyle);

// Obtener todas las respuestas a esta encuesta
$queryRespuestas = "SELECT r.idrespuestas, u.nombreU, r.fecha 
                    FROM enc_respuesta r
                    JOIN usuarios u ON r.idusuario = u.idusuario
                    WHERE r.idencuesta = ?
                    ORDER BY r.fecha ASC";
$stmtRespuestas = $mysqli->prepare($queryRespuestas);
$stmtRespuestas->bind_param("i", $idEncuesta);
$stmtRespuestas->execute();
$respuestas = $stmtRespuestas->get_result();

$fila = 7; // Empezamos a escribir datos desde la fila 7

while ($respuesta = $respuestas->fetch_assoc()) {
    $sheet->setCellValue('A' . $fila, $respuesta['idrespuestas']);
    $sheet->setCellValue('B' . $fila, $respuesta['nombreU']);
    $sheet->setCellValue('C' . $fila, $respuesta['fecha']);
    
    // Para cada pregunta, obtener la respuesta correspondiente
    foreach ($preguntasInfo as $idPregunta => $infoPregunta) {
        $columnaLetra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($infoPregunta['columna']);
        $celda = $columnaLetra . $fila;
        
        $respuestaTexto = "";
        
        if ($infoPregunta['tipo'] == 1 || $infoPregunta['tipo'] == 2) { // Tipos que usan texto
            $queryRespuestaTexto = "SELECT respuesta FROM enc_respuestatexto 
                                  WHERE idrespuestas = ? AND idpregunta = ?";
            $stmtTexto = $mysqli->prepare($queryRespuestaTexto);
            $stmtTexto->bind_param("ii", $respuesta['idrespuestas'], $idPregunta);
            $stmtTexto->execute();
            $resultTexto = $stmtTexto->get_result();
            
            if ($resultTexto->num_rows > 0) {
                $respuestaTexto = $resultTexto->fetch_assoc()['respuesta'];
            }
        } else { // Tipos que usan opciones
            $queryRespuestaOpcion = "SELECT o.opcion 
                                    FROM enc_respuestaopcion ro
                                    JOIN enc_opcion o ON ro.idopciones = o.idopciones
                                    WHERE ro.idrespuestas = ? AND ro.idpregunta = ?";
            $stmtOpcion = $mysqli->prepare($queryRespuestaOpcion);
            $stmtOpcion->bind_param("ii", $respuesta['idrespuestas'], $idPregunta);
            $stmtOpcion->execute();
            $resultOpcion = $stmtOpcion->get_result();
            
            $opciones = [];
            while ($rowOpcion = $resultOpcion->fetch_assoc()) {
                $opciones[] = $rowOpcion['opcion'];
            }
            $respuestaTexto = implode(", ", $opciones);
        }
        
        $sheet->setCellValue($celda, $respuestaTexto);
    }
    
    $fila++;
}

// Aplicar estilo a los datos
$sheet->getStyle('A7:' . $sheet->getHighestColumn() . ($fila-1))->applyFromArray($dataStyle);

// Autoajustar el ancho de las columnas
foreach (range('A', $sheet->getHighestColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Crear el archivo Excel y forzar la descarga
$filename = "encuesta_" . $encuesta['nombre'] . "_" . date('Y-m-d') . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>