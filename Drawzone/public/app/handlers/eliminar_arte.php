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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idDibujo'])) {
    $idDibujo = intval($_POST['idDibujo']);
    $idUsuario = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("SELECT link_dibujo FROM galeria_personal WHERE idDibujo = ? AND idUsuario = ?");
    $stmt->execute([$idDibujo, $idUsuario]);
    $dibujo = $stmt->fetch();

    if ($dibujo) {
        $url = $dibujo['link_dibujo'];
        $parsedUrl = parse_url($url);
        $path = pathinfo($parsedUrl['path']);
        $publicId = 'galerias/galerias_personales/' . $path['filename'];

        try {
            (new UploadApi())->destroy($publicId, ['resource_type' => 'image']);

            $delete = $pdo->prepare("DELETE FROM galeria_personal WHERE idDibujo = ? AND idUsuario = ?");
            $delete->execute([$idDibujo, $idUsuario]);

            header("Location: /DrawZone/public/perfil.php?success=Dibujo+eliminado+correctamente");
            exit;
        } catch (Exception $e) {
            error_log("❌ Error al eliminar: " . $e->getMessage());
            header("Location: /DrawZone/public/perfil.php?error=Error+al+eliminar+el+dibujo");
            exit;
        }
    } else {
        header("Location: /DrawZone/public/perfil.php?error=Dibujo+no+encontrado");
        exit;
    }
} else {
    header("Location: /DrawZone/public/perfil.php?error=Solicitud+inválida");
    exit;
}

function eliminarImagenDeCloudinary($publicId) {
    try {
        $resultado = (new UploadApi())->destroy($publicId, ['resource_type' => 'image']);
        return $resultado;
    } catch (Exception $e) {
        error_log("❌ Error al eliminar imagen de Cloudinary: " . $e->getMessage());
        return false;
    }
}