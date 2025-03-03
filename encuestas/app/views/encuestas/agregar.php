<?php include 'app/views/templates/header.php'; ?>
<h1>Crear Nueva Encuesta</h1>
<form action="encuestas/agregar" method="POST">
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" required>
    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" required></textarea>
    <button type="submit">Crear</button>
</form>
<?php include 'app/views/templates/footer.php'; ?>
