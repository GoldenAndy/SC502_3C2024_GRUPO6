<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario = $_SESSION["user"]["id"];
    $cliente = trim($_POST["cliente"] ?? '');
    $estado = $_POST["estado"] ?? 1;

    if (empty($cliente)) {
        header("Location: /DrawZone/public/perfil.php?error=Cliente+requerido");
        exit();
    }

    $usuarioModel = new Usuario();
    if ($usuarioModel->crearComision($idUsuario, $cliente, $estado)) {
        header("Location: /DrawZone/public/perfil.php?success=Comisión+añadida");
    } else {
        header("Location: /DrawZone/public/perfil.php?error=No+se+pudo+añadir+la+comisión");
    }
    exit();
}

header("Location: /DrawZone/public/perfil.php");
exit();