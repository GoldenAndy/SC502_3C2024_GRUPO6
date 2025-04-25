<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../../vendor/autoload.php"; // Cargar PHPMailer

// 🔹 Configuración de correo
define("EMAIL_USER", "drawzoneinc@gmail.com");
define("EMAIL_PASS", "kzzd xflw odum ybcv");

function enviarCorreoActivacion($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com"; 
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_USER;
        $mail->Password = EMAIL_PASS;
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        // Destinatario
        $mail->setFrom(EMAIL_USER, "DrawZone");
        $mail->addAddress($email);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = "Activa tu cuenta en DrawZone";
        $mail->Body = "
            <h2>Bienvenido a DrawZone!</h2>
            <p>Para activar tu cuenta, haz clic en el siguiente enlace:</p>
            <a href='http://localhost/DrawZone/public/app/handlers/procesar_activacion.php?token=$token' style='padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Activar cuenta</a>
            <p>Si no creaste esta cuenta, ignora este correo.</p>
        ";


        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}
