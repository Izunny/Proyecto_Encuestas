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

        
    <header class="header-container">
            
            <div class="header_one">
                <a href="/"> <img class="logo" src="./imagenes/logo4.webp" alt="logo"></a>    
               
                <h2 class="title_page">Encuestas Dinamicas</h2>
            </div>    
                
    </header>

    <h1 class="my-5">Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
    <p>
        <!-- Todavia no se agrega una función para cambiar la contraseña-->
        <a href="reset-password.php" class="btn btn-warning">Cambiar tu contraseña</a>
        <a href="logout.php" class="btn btn-danger ml-3">Termina tu sesión</a>
    </p>

    <ul>
        <li><a href="#">Inicio</a></li>
        <li><a href="#">Ver Ecnuestas</a></li>
        <li><a href="#">Ver Resultados </a></li>
        <li><a href="router.php?url=encuestas/agregar">Crear Encuesta</a></li>
    </ul>

    <main class="servicios">
        <h2>Servicicos</h2>
        <div class="servicio-1">
            <i class=""></i>
            <h3>Crear Encuestas</h3>
        </div>
        <div class="servicio-2">
            <i class=""></i>
            <h3>Responder Encuestas</h3>
        </div>
        <div class="servicio-3">
            <i class=""></i>
            <h3>Compartir Enceustas</h3>
        </div>
    </main>
</body>
</html>