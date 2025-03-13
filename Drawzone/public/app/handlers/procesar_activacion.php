<?php
require_once __DIR__ . "/../models/Usuario.php";

$usuarioModel = new Usuario();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if ($usuarioModel->activarCuenta($token)) {
        header("Location: /DrawZone/public/login.php?success=" . urlencode("Cuenta activada con éxito. Ahora puedes iniciar sesión."));
        exit();
    } else {
        header("Location: /DrawZone/public/login.php?error=" . urlencode("Token inválido o cuenta ya activada."));
        exit();
    }
} else {
    header("Location: /DrawZone/public/login.php?error=" . urlencode("Token no proporcionado."));
    exit();
}
