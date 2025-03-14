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

        
<?php include __DIR__ . "/includes/header.php"; ?>

    <h1 class="my-5">Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
    <p>
        <!-- Todavia no se agrega una función para cambiar la contraseña-->
        <a href="reset-password.php" class="btn btn-warning">Cambiar tu contraseña</a>
      
    </p>

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