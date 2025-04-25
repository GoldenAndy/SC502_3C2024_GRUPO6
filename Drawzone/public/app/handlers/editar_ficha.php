<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../config/cloudinary.php';

use Cloudinary\Api\Upload\UploadApi;

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioModel = new Usuario();
$idUsuario = $_SESSION['user']['id'];
$idFicha = $_GET['id'] ?? null;

if (!$idFicha) {
    die("⚠️ No se especificó ficha.");
}

$fichas = $usuarioModel->obtenerFichasPrecios($idUsuario);
$ficha = null;
foreach ($fichas as $f) {
    if ($f['idFicha'] == $idFicha) {
        $ficha = $f;
        break;
    }
}

if (!$ficha) {
    die("🚫 No puedes editar esta ficha.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoLink = $ficha['link_ficha'];

  
    if (!empty($_FILES['nueva_imagen']['tmp_name'])) {
        
        $upload = subirImagenGaleriaACloudinary($_FILES['nueva_imagen']['tmp_name'], "galerias/fichas_precios");

        if ($upload !== false) {
            $nuevoLink = $upload['secure_url'];

            
            $urlAnterior = $ficha['link_ficha'];
            $parsedUrl = parse_url($urlAnterior);
            $path = pathinfo($parsedUrl['path']);
            $publicId = 'galerias/fichas_precios/' . $path['filename'];

            try {
                (new UploadApi())->destroy($publicId, ['resource_type' => 'image']);
            } catch (Exception $e) {
                error_log("❌ Error al borrar imagen anterior: " . $e->getMessage());
            }
        }
    }

    
    $usuarioModel->actualizarFichaPrecio($idFicha, $nuevoLink);

    header("Location: /DrawZone/public/perfil.php?success=Ficha+editada+correctamente");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Ficha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Editar Ficha</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Ficha Actual:</label><br>
            <img src="<?php echo htmlspecialchars($ficha['link_ficha']); ?>" class="img-fluid" style="max-width: 300px;">
        </div>
        <div class="mb-3">
            <label class="form-label">Nueva Imagen (opcional):</label>
            <input type="file" name="nueva_imagen" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="/DrawZone/public/perfil.php" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
