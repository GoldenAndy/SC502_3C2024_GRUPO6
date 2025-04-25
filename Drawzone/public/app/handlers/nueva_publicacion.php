<?php
session_start();
require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../../config/cloudinary.php";

if (!isset($_SESSION["user"])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario = $_SESSION["user"]["id"];
    $contenido = trim($_POST["contenido"] ?? '');

    if (empty($contenido)) {
        header("Location: /DrawZone/public/perfil.php?error=La+publicación+no+puede+estar+vacía");
        exit();
    }

    $linkImagen = null;

    if (isset($_FILES["imagen"]) && !empty($_FILES["imagen"]["tmp_name"])) {
        $resultado = subirImagenGaleriaACloudinary($_FILES["imagen"]["tmp_name"], "galerias/publicaciones_muro");

        if ($resultado && isset($resultado["secure_url"])) {
            $linkImagen = $resultado["secure_url"];
        } else {
            header("Location: /DrawZone/public/perfil.php?error=Error+al+subir+la+imagen+a+Cloudinary");
            exit();
        }
    }

    $usuarioModel = new Usuario();
    $usuarioModel->guardarPublicacionMuro($idUsuario, $contenido, $linkImagen);

    header("Location: /DrawZone/public/perfil.php?success=¡Publicación+realizada!");
    exit();
}

header("Location: /DrawZone/public/perfil.php");
exit();
