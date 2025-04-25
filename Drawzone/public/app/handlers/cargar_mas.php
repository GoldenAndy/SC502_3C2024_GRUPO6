<?php
require_once __DIR__ . "/../models/Usuario.php";
$usuarioModel = new Usuario();

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 400;

$busqueda = $_GET['busqueda'] ?? null;
$estilos = $_GET['estilos'] ?? [];
$ilustraciones = $_GET['ilustraciones'] ?? [];
$coloreados = $_GET['coloreados'] ?? [];
$tipo_usuario = $_GET['tipo_usuario'] ?? [];
$precio_min = isset($_GET['precio_min']) ? floatval($_GET['precio_min']) : null;
$precio_max = isset($_GET['precio_max']) ? floatval($_GET['precio_max']) : null;

$hayFiltros = !empty($busqueda) || !empty($estilos) || !empty($ilustraciones) || !empty($coloreados) || $precio_min !== null || $precio_max !== null;

if (!empty($tipo_usuario)) {
    $resultados = $usuarioModel->buscarUsuariosPorFiltrosAvanzados([
        'busqueda' => $busqueda,
        'tipo_usuario' => $tipo_usuario,
        'estilos' => $estilos,
        'ilustraciones' => $ilustraciones,
        'coloreados' => $coloreados,
        'precio_min' => $precio_min,
        'precio_max' => $precio_max,
        'offset' => $offset,
        'limite' => $limite
    ]);
} elseif ($hayFiltros) {
    $resultados = $usuarioModel->buscarDibujosPorFiltrosAvanzados([
        'busqueda' => $busqueda,
        'estilos' => $estilos,
        'ilustraciones' => $ilustraciones,
        'coloreados' => $coloreados,
        'precio_min' => $precio_min,
        'precio_max' => $precio_max,
        'offset' => $offset,
        'limite' => $limite
    ]);
} else {
    $resultados = $usuarioModel->obtenerGaleriaDestacada($limite, $offset);
}

header('Content-Type: application/json');
echo json_encode($resultados);
exit;
