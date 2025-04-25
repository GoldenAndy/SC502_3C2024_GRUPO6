<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$idUsuario = $_SESSION['user']['id'];
$idRedSocial = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idRedSocial > 0) {
    $usuarioModel = new Usuario();
    $usuarioModel->eliminarRedSocial($idUsuario, $idRedSocial);
}

header("Location: /DrawZone/public/editar_cuenta.php");
exit();
