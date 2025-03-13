<?php
require './config/cloudinary.php'; // Cargar la configuración de Cloudinary

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
    $fileTmpPath = $_FILES['imagen']['tmp_name']; // Obtener la ruta temporal del archivo

    // Intentar subir la imagen a Cloudinary
    $resultado = subirImagenACloudinary($fileTmpPath, "drawzone_perfiles");

    if ($resultado) {
        echo "<h2 style='color: green;'>✅ Imagen subida correctamente</h2>";
        echo "<p><strong>URL:</strong> <a href='" . $resultado['secure_url'] . "' target='_blank'>" . $resultado['secure_url'] . "</a></p>";
        echo "<img src='" . $resultado['secure_url'] . "' alt='Imagen subida' width='200'>";
    } else {
        echo "<h2 style='color: red;'>❌ Error al subir la imagen</h2>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test Cloudinary</title>
</head>
<body>
    <h1>🔹 Prueba de Subida a Cloudinary</h1>
    <form action="test_cloudinary.php" method="POST" enctype="multipart/form-data">
        <label for="imagen">Selecciona una imagen:</label>
        <input type="file" name="imagen" required>
        <button type="submit">Subir Imagen</button>
    </form>
</body>
</html>
