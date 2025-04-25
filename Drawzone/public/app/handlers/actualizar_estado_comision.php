<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario = $_SESSION['user']['id'];
    $idComision = $_POST['idComision'] ?? null;
    $nuevoEstado = $_POST['estado'] ?? null;

    if ($idComision && $nuevoEstado !== null) {
        $usuarioModel = new Usuario();
        $usuarioModel->actualizarEstadoComision($idUsuario, $idComision, $nuevoEstado);
    }
}

header("Location: /DrawZone/public/perfil.php");
exit();
