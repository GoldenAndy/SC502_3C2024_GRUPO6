<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . "/../../config/cloudinary.php";

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioModel = new Usuario();
$idUsuario = $_SESSION['user']['id'];
$fichasExistentes = $usuarioModel->obtenerFichasPrecios($idUsuario);
$esPrimeraVez = empty($fichasExistentes);

if (!isset($_FILES['fichas'])) {
    $_SESSION['error'] = "No se han subido archivos.";
    header("Location: ../../perfil.php");
    exit();
}

foreach ($_FILES['fichas']['tmp_name'] as $index => $tmpName) {
    if (!empty($tmpName)) {
        $resultado = subirImagenGaleriaACloudinary($tmpName, "galerias/fichas_precios");

        if ($resultado !== false && isset($resultado['secure_url'])) {
            $linkFicha = $resultado['secure_url'];
            $usuarioModel->insertarFichaPrecios($idUsuario, $linkFicha);
        }
    }
}

header("Location: /DrawZone/public/perfil.php");
exit();
