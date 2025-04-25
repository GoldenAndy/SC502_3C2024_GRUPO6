<?php
session_start();
require_once __DIR__ . "/../../app/models/Usuario.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../../login.php?error=" . urlencode("Todos los campos son obligatorios."));
        exit();
    }

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->obtenerUsuarioPorEmail($email);


    if (!$usuario) {
        header("Location: ../../login.php?error=" . urlencode("Correo o contraseña incorrectos."));
        exit();
    }


    if (!password_verify($password, $usuario["password"])) {
        header("Location: ../../login.php?error=" . urlencode("Correo o contraseña incorrectos."));
        exit();
    }


    if ($usuario["estado"] == 0) {
        header("Location: ../../login.php?error=" . urlencode("Debes activar tu cuenta antes de iniciar sesión."));
        exit();
    }


    $_SESSION["user"] = [
        "id" => $usuario["idUsuario"],
        "name" => $usuario["usuario"],
        "email" => $usuario["email"],
        "profile_img" => $usuario["imagen_perfil"],
        "rol" => $usuario["rol"]
    ];


    header("Location: ../../index.php");
    exit();
} else {
    header("Location: ../../login.php");
    exit();
}
