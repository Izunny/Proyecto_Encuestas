<?php 
include dirname(__DIR__) . "/templates/header.php"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo '/encuestas/assets/css/style.css'; ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>
<body>

<nav>
    <ul class="nav-menu">
        <li><a href="router.php?url=dashboard">Inicio</a></li>
        <li><a href="router.php?url=encuestas">Ver Encuestas</a></li>
        <li><a href="#">Ver Resultados</a></li>
        <li><a href="router.php?url=encuestas/agregar">Crear Encuesta</a></li>
    </ul>
</nav>


<main class="container">
    <h1>Encuestas Disponibles</h1>
    
    <?php if (!empty($encuestas)): ?>
        <ul>
            <?php foreach ($encuestas as $encuesta): ?>
                <li>
                    <strong><?= htmlspecialchars($encuesta['nombre']) ?></strong>
                    <a href="router.php?url=encuestas/ver&id=<?= $encuesta['idencuesta'] ?>">Ver</a> |
                    <a href="router.php?url=encuestas/editar&id=<?= $encuesta['idencuesta'] ?>">Editar</a> |
                    <a href="router.php?url=encuestas/eliminar&id=<?= $encuesta['idencuesta'] ?>" 
                       onclick="return confirm('¿Eliminar esta encuesta?')">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No hay encuestas disponibles.</p>
    <?php endif; ?>

</main>



<?php include dirname(__DIR__) . "/templates/footer.php"; ?>
</body>
</html>
