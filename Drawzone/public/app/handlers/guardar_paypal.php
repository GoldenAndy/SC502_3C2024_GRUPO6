<?php
session_start();
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../config/cloudinary.php";

if (!isset($_SESSION['user'])) {
    header('Location: /DrawZone/public/login.php');
    exit();
}

$idUsuario = $_SESSION['user']['id'];
$link = trim($_POST['linkPaypal'] ?? '');

if (!preg_match('/^https:\/\/www\.paypal\.me\/[a-zA-Z0-9.-]+$/', $link)) {
    die('Enlace inválido. Asegúrate de usar el formato correcto: https://www.paypal.me/tuusuario');
}

$pdo = Database::getConnection();

$stmt = $pdo->prepare("INSERT INTO paypal_links (idUsuario, link_paypal) VALUES (?, ?)
                       ON DUPLICATE KEY UPDATE link_paypal = VALUES(link_paypal)");
$stmt->execute([$idUsuario, $link]);

header("Location: /DrawZone/public/perfil.php");
exit();
