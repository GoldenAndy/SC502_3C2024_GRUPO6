<?php
session_start();
require_once __DIR__ . '/../../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$idUsuario1 = $_SESSION['user']['id'];
$idUsuario2 = $_GET['idDestino'] ?? null;

if (!$idUsuario2 || $idUsuario1 == $idUsuario2) {
    header("Location: /DrawZone/public/index.php");
    exit();
}

$usuarioModel = new Usuario();

$idConversacion = $usuarioModel->buscarConversacion($idUsuario1, $idUsuario2);

if (!$idConversacion) {
    $idConversacion = $usuarioModel->crearConversacion($idUsuario1, $idUsuario2);
}

header("Location: /DrawZone/public/mensajes.php?idConversacion=$idConversacion");
exit();
