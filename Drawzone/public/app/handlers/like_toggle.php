<?php
require_once '../models/Usuario.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$idPublicacion = $data['idPublicacion'] ?? null;
$idUsuario = $_SESSION['user']['id'];

if (!$idPublicacion) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

$model = new Usuario();
$resultado = $model->alternarLikePublicacion($idUsuario, $idPublicacion);

echo json_encode([
    'success' => true,
    'nuevosLikes' => $resultado['likes'],
    'dioLike' => $resultado['dioLike']
]);
