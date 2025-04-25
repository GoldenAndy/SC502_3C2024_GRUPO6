<?php
session_start();
date_default_timezone_set('America/Costa_Rica');
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit();
}

$usuarioModel = new Usuario();
$idRemitente = $_SESSION['user']['id'];

if (!empty($_POST['idConversacion']) && !empty($_POST['contenido'])) {
    $idConversacion = intval($_POST['idConversacion']);
    $contenido = trim($_POST['contenido']);

    $usuarioModel->guardarMensaje($idConversacion, $idRemitente, $contenido);

    echo json_encode([
        'success' => true,
        'contenido' => $contenido,
        'hora' => date("h:i A")
    ], JSON_UNESCAPED_UNICODE);

    exit();
}

echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
exit();
