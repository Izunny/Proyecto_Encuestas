<?php
// Se inicia la sesión
session_start();
 
// Se verifica si el usuario ya ha ingresado, si no entonces se redirecciona
// a la pagina de inicio
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

    <!-- Se muestra los datos principales de todas las encuestas -->
    <p>
    <?php 
        $username = "root"; 
        $password = ""; 
        $database = "db_encuestas"; 
        $mysqli = new mysqli("localhost", $username, $password, $database); 
        $query = "SELECT * FROM enc_encuestasm INNER JOIN usuarios ON enc_encuestasm.idusuario=usuarios.idusuario ORDER BY idencuesta ASC";


        $tabla_enc = '<table class="table-welcome"> 
            <tr> 
                <td> <font face="Arial">ID Encuesta</font> </td> 
                <td> <font face="Arial">Nombre de<br>encuesta</font> </td> 
                <td> <font face="Arial">Descripcion</font> </td> 
                <td> <font face="Arial">Autor</font> </td> 
                <td> <font face="Arial">Fecha</font> </td> 
            </tr>';

        if ($result = $mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) {
                
                $field1name = $row["idencuesta"];
                $field2name = $row["nombre"];
                $field3name = $row["descripcion"];
                $field4name = $row["nombreU"];
                $field5name = $row["fecha"]; 

                $tabla_enc .= '<tr> 
                        <td>'.$field1name.'</td> 
                        <td>'.$field2name.'</td> 
                        <td>'.$field3name.'</td> 
                        <td>'.$field4name.'</td> 
                        <td>'.$field5name.'</td> 
                    </tr>';
            }
            $tabla_enc .= '</table>';
            $result->free();
        } 
    ?>
    </p>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" hreF="./assets/css/style.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

       
    <?php include __DIR__ . "/includes/header.php"; ?>
    <div class="items-welcome">
        <div class="wrap-welcome">
            <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
        </div>

        <div class="wrap-welcome">
            <h2>Encuestas activas</h2>
            <p> <?php echo $tabla_enc; ?> </p>
        </div>
    </div>
    <nav>
        <ul class="nav-menu">
            <li><a href="#">Ver Encuestas</a></li>
            <li><a href="#">Ver Resultados</a></li>
            <li><a href="editar.php">Editar Encuesta</a></li>
            <li><a href="agregar.php">Crear Encuesta</a></li>
        </ul>
    </nav>

</body>
</html>