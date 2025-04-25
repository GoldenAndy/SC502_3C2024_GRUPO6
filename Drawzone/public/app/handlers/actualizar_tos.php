<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario = $_SESSION['user']['id'];
    $tos = trim($_POST['tos'] ?? '');

    if (empty($tos)) {
        header("Location: /DrawZone/public/perfil.php?error=El+TOS+no+puede+estar+vacío");
        exit();
    }

    $usuarioModel = new Usuario();

    if ($usuarioModel->actualizarTOS($idUsuario, $tos)) {
        header("Location: /DrawZone/public/perfil.php?success=Términos+de+Servicio+actualizados");
    } else {
        header("Location: /DrawZone/public/perfil.php?error=No+se+pudo+actualizar+el+TOS");
    }
    exit();
}

header("Location: /DrawZone/public/perfil.php");
exit();
