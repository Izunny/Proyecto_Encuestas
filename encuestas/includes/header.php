<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuestas Dinámicas</title>
    <link rel="stylesheet" href="/encuestas/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header class="header-container">
        <div class="header_one">
            <a href="/encuestas/index.php">
                <img class="logo" src="/encuestas/imagenes/logo4.webp" alt="logo">
            </a>
            <h2 class="title_page">Encuestas Dinámicas</h2>
        </div>
        <div class="header_two">

            <?php if (isset($_SESSION['idusuario'])): ?>
                <a href="/encuestas/logout.php"><button class="index_button_two">Cerrar sesión</button></a>
            <?php else: ?>
                <a href="/encuestas/login.php"><button class="index_button_one">Iniciar sesión</button></a>
                <a href="/encuestas/register.php"><button class="index_button_two">Registro</button></a>
            <?php endif; ?>
        </div>
    </header>
