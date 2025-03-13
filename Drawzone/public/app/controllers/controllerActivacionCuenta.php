<?php
require_once __DIR__ . '/../models/Usuario.php';

$usuarioModel = new Usuario();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if ($usuarioModel->activarCuenta($token)) {
        echo "<h2 style='color: green;'>✅ Cuenta activada con éxito</h2>";
        echo "<p>¡Tu cuenta ha sido activada! Ahora puedes <a href='/DrawZone/public/login.php'>iniciar sesión</a>.</p>";
    } else {
        echo "<h2 style='color: red;'>❌ Token inválido o cuenta ya activada</h2>";
    }
} else {
    echo "<h2 style='color: red;'>⚠️ Token no proporcionado</h2>";
}
