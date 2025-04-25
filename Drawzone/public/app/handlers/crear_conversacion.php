<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioDestino = trim($_POST['usuarioDestino']);
$usuarioModel = new Usuario();
$idUsuario1 = $_SESSION['user']['id'];

$idUsuario2 = $usuarioModel->obtenerIdPorNombre($usuarioDestino);

if (!$idUsuario2 || $idUsuario2 == $idUsuario1) {
    
    header("Location: /DrawZone/public/mensajes.php?error=Usuario+no+válido");
    exit();
}

$idConversacion = $usuarioModel->buscarConversacion($idUsuario1, $idUsuario2);
if (!$idConversacion) {
    $idConversacion = $usuarioModel->crearConversacion($idUsuario1, $idUsuario2);
}


header("Location: /DrawZone/public/mensajes.php?id=" . $idConversacion);
exit();
