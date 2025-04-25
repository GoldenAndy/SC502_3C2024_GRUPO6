<?php
session_start();
require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../../config/cloudinary.php";

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idCliente = $_SESSION["user"]["id"];
    $idArtista = $_POST["idArtista"] ?? null;
    $calificacion = $_POST["calificacion"] ?? null;
    $comentario = trim($_POST["comentario"] ?? '');
    $archivoTmp = $_FILES["imagen_resultado"]["tmp_name"] ?? '';

    if (empty($idArtista) || empty($calificacion) || empty($comentario)) {
        header("Location: /DrawZone/public/perfil.php?id=$idArtista&error=Todos+los+campos+son+obligatorios");
        exit();
    }

    if ($idCliente == $idArtista) {
        header("Location: /DrawZone/public/perfil.php?id=$idArtista&error=No+puedes+reseñarte+a+ti+mismo");
        exit();
    }

    $linkImagen = null;

    if (!empty($archivoTmp)) {
        $resultado = (new Cloudinary\Api\Upload\UploadApi())->upload($archivoTmp, [
            "folder" => "reviews"
        ]);

        if ($resultado && isset($resultado["secure_url"])) {
            $linkImagen = $resultado["secure_url"];
        } else {
            header("Location: /DrawZone/public/perfil.php?id=$idArtista&error=Error+al+subir+la+imagen+a+Cloudinary");
            exit();
        }
    }

    $usuarioModel = new Usuario();
    $exito = $usuarioModel->guardarReview($idArtista, $idCliente, $calificacion, $comentario, $linkImagen);

    if ($exito) {
        header("Location: /DrawZone/public/perfil.php?id=$idArtista&success=¡Reseña+enviada+correctamente!");
        exit();
    } else {
        header("Location: /DrawZone/public/perfil.php?id=$idArtista&error=Error+al+guardar+la+reseña");
        exit();
    }
}

header("Location: /DrawZone/public/perfil.php");
exit();
