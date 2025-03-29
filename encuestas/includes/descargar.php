
<?php
    // Para descargar el archivo excel (es automatico, se guarda en la carpeta encuestas)
    require __DIR__ . 'encuestas/vendor/autoload.php';
    $xlsx = Shuchkin\SimpleXLSXGen::fromArray( $tabla );
    $xlsx->saveAs($nombre.'.xlsx'); 
?>
