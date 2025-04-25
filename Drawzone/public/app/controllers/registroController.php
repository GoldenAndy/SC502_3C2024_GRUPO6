<?php
require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../../config/cloudinary.php";
require_once __DIR__ . "/../../config/email.php";

class RegistroController {
    public function procesarRegistro() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirm-password"];
            $rol = $_POST["rol"];

            
            $redesSociales = [];
            if ($rol === "artista" || $rol === "ambos") {
                if (!empty($_POST["twitter"])) $redesSociales["Twitter"] = trim($_POST["twitter"]);
                if (!empty($_POST["instagram"])) $redesSociales["Instagram"] = trim($_POST["instagram"]);
                if (!empty($_POST["facebook"])) $redesSociales["Facebook"] = trim($_POST["facebook"]);
            }

            
            $imagenPerfil = null;
            if (!empty($_FILES["imagen"]["tmp_name"])) {
                $resultado = subirImagenACloudinary($_FILES["imagen"]["tmp_name"], "drawzone_perfiles");
                if ($resultado) {
                    $imagenPerfil = $resultado["secure_url"];
                } else {
                    error_log("❌ Error al subir imagen a Cloudinary.");
                }
            }

            
            $token = bin2hex(random_bytes(32));

            
            if (empty($usuario) || empty($email) || empty($password) || empty($confirmPassword)) {
                header("Location: /DrawZone/public/register.php?error=" . urlencode("Todos los campos son obligatorios."));
                exit();
            }

            if ($password !== $confirmPassword) {
                header("Location: /DrawZone/public/register.php?error=" . urlencode("Las contraseñas no coinciden."));
                exit();
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            
            $usuarioModel = new Usuario();

            
            if ($usuarioModel->usuarioExiste($usuario, $email)) {
                header("Location: /DrawZone/public/register.php?error=" . urlencode("El usuario o email ya están registrados."));
                exit();
            }

            
            $idUsuario = $usuarioModel->registrarUsuario($usuario, $email, $passwordHash, $rol, $imagenPerfil, $token);

            if ($idUsuario) {
                
                if (!empty($redesSociales)) {
                    $usuarioModel->guardarRedesSociales($idUsuario, $redesSociales);
                }

                
                if (enviarCorreoActivacion($email, $token)) {
                    header("Location: /DrawZone/public/login.php?success=" . urlencode("Registro exitoso, revisa tu correo para activar tu cuenta."));
                } else {
                    header("Location: /DrawZone/public/register.php?error=" . urlencode("El registro fue exitoso, pero hubo un error enviando el correo."));
                }
                exit();
            } else {
                header("Location: /DrawZone/public/register.php?error=" . urlencode("Error al registrar usuario."));
                exit();
            }
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $registro = new RegistroController();
    $registro->procesarRegistro();
}
