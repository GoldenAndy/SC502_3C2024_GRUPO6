<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idPublicacion'])) {
    $idPublicacion = $_POST['idPublicacion'];
    $usuarioModel = new Usuario();

    if ($usuarioModel->aumentarLikesPublicacion($idPublicacion)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No se pudo dar like"]);
    }
    exit();
}

echo json_encode(["success" => false, "error" => "Petición inválida"]);
exit();