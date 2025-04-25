<?php
session_start();

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/cloudinary.php";
require_once __DIR__ . "/../models/Usuario.php";

use Cloudinary\Api\Upload\UploadApi;

$pdo = Database::getConnection();

if (!isset($_SESSION["user"])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["idPublicacion"])) {
    $idUsuario = $_SESSION["user"]["id"];
    $idPublicacion = intval($_POST["idPublicacion"]);
    $contenido = trim($_POST["contenido"] ?? '');

    
    if (empty($contenido)) {
        header("Location: /DrawZone/public/perfil.php?error=La+publicación+no+puede+estar+vacía");
        exit();
    }

    
    $stmt = $pdo->prepare("SELECT imagen FROM muro_publicaciones WHERE idPublicacion = ? AND idUsuario = ?");
    $stmt->execute([$idPublicacion, $idUsuario]);
    $publicacion = $stmt->fetch();

    if (!$publicacion) {
        header("Location: /DrawZone/public/perfil.php?error=Publicación+no+encontrada");
        exit();
    }

    $linkNuevaImagen = $publicacion["imagen"];

    
    if (isset($_FILES["imagen"]) && !empty($_FILES["imagen"]["tmp_name"])) {
       
        if (!empty($publicacion["imagen"])) {
            $url = $publicacion["imagen"];
            $parsedUrl = parse_url($url);
            $path = pathinfo($parsedUrl['path']);
            $publicId = 'galerias/publicaciones_muro/' . $path['filename'];

            eliminarImagenDeCloudinary($publicId);
        }

        
        try {
            $resultado = (new UploadApi())->upload($_FILES["imagen"]["tmp_name"], [
                "folder" => "galerias/publicaciones_muro",
                "resource_type" => "image",
                "use_filename" => true,
                "unique_filename" => false,
                "overwrite" => false
            ]);

            if ($resultado && isset($resultado["secure_url"])) {
                $linkNuevaImagen = $resultado["secure_url"];
            } else {
                header("Location: /DrawZone/public/perfil.php?error=Error+al+subir+la+imagen+a+Cloudinary");
                exit();
            }
        } catch (Exception $e) {
            error_log("❌ Error al subir nueva imagen: " . $e->getMessage());
            header("Location: /DrawZone/public/perfil.php?error=Error+inesperado+al+subir+imagen");
            exit();
        }
    }

    
    $stmt = $pdo->prepare("UPDATE muro_publicaciones SET contenido = ?, imagen = ? WHERE idPublicacion = ? AND idUsuario = ?");
    $stmt->execute([$contenido, $linkNuevaImagen, $idPublicacion, $idUsuario]);

    header("Location: /DrawZone/public/perfil.php?success=¡Publicación+editada+correctamente!");
    exit();
}

header("Location: /DrawZone/public/perfil.php");
exit();



function eliminarImagenDeCloudinary($publicId) {
    try {
        $resultado = (new UploadApi())->destroy($publicId, ['resource_type' => 'image']);
        return $resultado;
    } catch (Exception $e) {
        error_log("❌ Error al eliminar imagen de Cloudinary: " . $e->getMessage());
        return false;
    }
}
