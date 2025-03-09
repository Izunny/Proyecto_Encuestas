<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Encuestas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Control de Encuestas</h1>
    
    <a href="router.php?url=encuestas/agregar" class="btn">Agregar Encuesta</a>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($encuestas as $encuesta): ?>
                <tr>
                    <td><?= $encuesta['id'] ?></td>
                    <td><?= htmlspecialchars($encuesta['titulo']) ?></td>
                    <td><?= htmlspecialchars($encuesta['descripcion']) ?></td>
                    <td>
                        <a href="router.php?url=encuestas/eliminar&id=<?= $encuesta['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta encuesta?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
