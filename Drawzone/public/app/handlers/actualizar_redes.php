<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioModel = new Usuario();
$idUsuario = $_SESSION['user']['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['redes'])) {
    $redes = $_POST['redes'];

    $redesFiltradas = array_filter($redes, function ($link) {
        return !empty(trim($link));
    });

    if (!empty($redesFiltradas)) {
        $usuarioModel->actualizarRedesSociales($idUsuario, $redesFiltradas);
    }
}

header("Location: /DrawZone/public/editar_cuenta.php");
exit();
