<?php
session_start();
require_once __DIR__ . "/../../app/models/Usuario.php"; // Incluir el modelo de usuario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../../login.php?error=" . urlencode("Todos los campos son obligatorios."));
        exit();
    }

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->obtenerUsuarioPorEmail($email);

    // Verificar si el usuario existe en la BD
    if (!$usuario) {
        header("Location: ../../login.php?error=" . urlencode("Correo o contraseña incorrectos."));
        exit();
    }

    // Verificar la contraseña hasheada
    if (!password_verify($password, $usuario["password"])) {
        header("Location: ../../login.php?error=" . urlencode("Correo o contraseña incorrectos."));
        exit();
    }

    // Guardar los datos del usuario en la sesión
    $_SESSION["user"] = [
        "id" => $usuario["idUsuario"],
        "name" => $usuario["usuario"],
        "email" => $usuario["email"],
        "profile_img" => $usuario["imagen_perfil"],
        "rol" => $usuario["rol"]
    ];

    // 🔀 Redirigir al index de usuario
    header("Location: ../../index.php");
    exit();
} else {
    header("Location: ../../login.php");
    exit();
}
