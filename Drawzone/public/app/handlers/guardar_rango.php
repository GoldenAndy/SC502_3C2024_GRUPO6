<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$idUsuario = $_SESSION['user']['id'];
$min = $_POST['precio_minimo'];
$max = $_POST['precio_maximo'];

if (!is_numeric($min) || !is_numeric($max) || $min < 0 || $max < 0 || $min > $max) {
    $_SESSION['error'] = "Rango de precios inválido.";
    header("Location: ../../perfil.php");
    exit();
}

$usuarioModel = new Usuario();
$existeRango = $usuarioModel->obtenerRangoPrecios($idUsuario);

if ($existeRango) {
    $usuarioModel->actualizarRangoPrecios($idUsuario, $min, $max);
} else {
    $usuarioModel->insertarRangoPrecios($idUsuario, $min, $max);
}

header("Location: /DrawZone/public/perfil.php");
exit();
