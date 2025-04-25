<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$idUsuario   = $_SESSION['user']['id'];
$idTipoRed   = isset($_POST['idTipoRed']) ? (int) $_POST['idTipoRed'] : 0;
$link        = isset($_POST['link_redSocial']) ? trim($_POST['link_redSocial']) : '';

if ($idTipoRed > 0 && !empty($link)) {
    $usuarioModel = new Usuario();
    $usuarioModel->agregarRedSocial($idUsuario, $idTipoRed, $link);
}

header("Location: ../../editar_cuenta.php");
exit();
