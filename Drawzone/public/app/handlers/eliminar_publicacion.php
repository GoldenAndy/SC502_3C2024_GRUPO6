<?php
session_start();

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/cloudinary.php";

use Cloudinary\Api\Upload\UploadApi;

$pdo = Database::getConnection();

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idPublicacion'])) {
    $idPublicacion = intval($_POST['idPublicacion']);
    $idUsuario = $_SESSION['user']['id'];

    
    $stmt = $pdo->prepare("SELECT imagen FROM muro_publicaciones WHERE idPublicacion = ? AND idUsuario = ?");
    $stmt->execute([$idPublicacion, $idUsuario]);
    $publicacion = $stmt->fetch();

    if ($publicacion) {
       
        $url = $publicacion['imagen'];
        if (!empty($url)) {
            $parsedUrl = parse_url($url);
            $path = pathinfo($parsedUrl['path']);
            $publicId = 'galerias/publicaciones_muro/' . $path['filename'];

            try {
                
                (new UploadApi())->destroy($publicId, ['resource_type' => 'image']);
            } catch (Exception $e) {
                error_log("❌ Error al eliminar imagen de Cloudinary: " . $e->getMessage());
                
            }
        }

        $delete = $pdo->prepare("DELETE FROM muro_publicaciones WHERE idPublicacion = ? AND idUsuario = ?");
        $delete->execute([$idPublicacion, $idUsuario]);

        header("Location: /DrawZone/public/perfil.php?success=¡Publicación+eliminada+correctamente!");
        exit;
    } else {
        header("Location: /DrawZone/public/perfil.php?error=Publicación+no+encontrada");
        exit();
    }
} else {
    header("Location: /DrawZone/public/perfil.php?error=Solicitud+inválida");
    exit();
}
