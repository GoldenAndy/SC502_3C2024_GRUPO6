<?php
session_start();
require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../../config/cloudinary.php";

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario = $_SESSION["user"]["id"];
    $titulo = trim($_POST["titulo"] ?? '');
    $idEstilo = $_POST["idEstilo"] ?? '';
    $idIlustracion = $_POST["idIlustracion"] ?? '';
    $idColoreado = $_POST["idColoreado"] ?? '';
    $archivoTmp = $_FILES["dibujo"]["tmp_name"] ?? '';

    if (empty($titulo) || empty($idEstilo) || empty($idIlustracion) || empty($idColoreado) || empty($archivoTmp)) {
        header("Location: /DrawZone/public/perfil.php?error=Todos+los+campos+son+obligatorios");
        exit();
    }

    $resultado = subirImagenGaleriaACloudinary($archivoTmp);

    if ($resultado && isset($resultado["secure_url"])) {
        $link = $resultado["secure_url"];

        $usuarioModel = new Usuario();
        $exito = $usuarioModel->guardarDibujoGaleria($idUsuario, $titulo, $link, $idEstilo, $idIlustracion, $idColoreado);

        if ($exito) {
            header("Location: /DrawZone/public/perfil.php?success=Imagen+subida+correctamente");
            exit();
        } else {
            header("Location: /DrawZone/public/perfil.php?error=Error+al+guardar+en+la+base+de+datos");
            exit();
        }
    } else {
        header("Location: /DrawZone/public/perfil.php?error=Error+al+subir+la+imagen+a+Cloudinary");
        exit();
    }
}

header("Location: /DrawZone/public/perfil.php");
exit();
