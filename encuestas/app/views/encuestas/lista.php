<?php include 'app/views/templates/header.php'; ?>
<h1>Encuestas Disponibles</h1>
<ul>
    <?php foreach ($encuestas as $encuesta): ?>
        <li>
            <?= $encuesta['titulo'] ?> - 
            <a href="encuestas/ver?id=<?= $encuesta['id'] ?>">Ver</a> |
            <a href="encuestas/eliminar?id=<?= $encuesta['id'] ?>" onclick="return confirm('¿Eliminar encuesta?')">Eliminar</a>
        </li>
    <?php endforeach; ?>
</ul>
<a href="encuestas/agregar">Crear Nueva Encuesta</a>
<?php include 'app/views/templates/footer.php'; ?>
