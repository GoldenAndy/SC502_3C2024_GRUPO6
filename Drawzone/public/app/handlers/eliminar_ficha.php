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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idFicha'])) {
    $idFicha = $_POST['idFicha'];

    $fichas = $usuarioModel->obtenerFichasPrecios($idUsuario);
    foreach ($fichas as $ficha) {
        if ($ficha['idFicha'] == $idFicha) {
            
            $url = $ficha['link_ficha'];
            $parsedUrl = parse_url($url);
            $path = pathinfo($parsedUrl['path']);
            $publicId = 'galerias/fichas_precios/' . $path['filename'];

            try {
                (new UploadApi())->destroy($publicId, ['resource_type' => 'image']);
            } catch (Exception $e) {
                error_log("❌ Error al borrar ficha de Cloudinary: " . $e->getMessage());
            }

            $usuarioModel->eliminarFicha($idFicha);
            break;
        }
    }

    header("Location: /DrawZone/public/perfil.php?success=Ficha+eliminada+correctamente");
    exit();
}

if (isset($_POST['fichas_a_eliminar']) && is_array($_POST['fichas_a_eliminar'])) {
    foreach ($_POST['fichas_a_eliminar'] as $idFicha) {
        $fichas = $usuarioModel->obtenerFichasPrecios($idUsuario);
        foreach ($fichas as $ficha) {
            if ($ficha['idFicha'] == $idFicha) {
                
                $url = $ficha['link_ficha'];
                $parsedUrl = parse_url($url);
                $path = pathinfo($parsedUrl['path']);
                $publicId = 'galerias/fichas_precios/' . $path['filename'];

                try {
                    (new UploadApi())->destroy($publicId, ['resource_type' => 'image']);
                } catch (Exception $e) {
                    error_log("❌ Cloudinary multiple: " . $e->getMessage());
                }

                $usuarioModel->eliminarFicha($idFicha);
            }
        }
    }

    header("Location: /DrawZone/public/perfil.php?success=Fichas+eliminadas");
    exit();
}

header("Location: /DrawZone/public/perfil.php?error=Acceso+no+válido");
exit();
