<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';

$usuarioModel = new Usuario();

$modo = $_GET['modo'] ?? 'recientes';
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limite = 9;

$excluirIds = [];
if ($modo === 'aleatorio' && isset($_GET['excluir_ids']) && is_array($_GET['excluir_ids'])) {
    $excluirIds = array_map('intval', $_GET['excluir_ids']);
}

$dibujos = ($modo === 'aleatorio') 
    ? $usuarioModel->obtenerDibujosAleatorios($limite, $excluirIds)
    : $usuarioModel->obtenerDibujosRecientes($limite, $offset);

ob_start();
foreach ($dibujos as $arte): ?>
    <div class="col">
        <a href="perfil.php?id=<?php echo $arte['idUsuario']; ?>" class="text-decoration-none">
            <div class="card shadow-sm">
                <img src="<?php echo htmlspecialchars($arte['link_dibujo']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($arte['titulo']); ?>">
                <div class="card-body text-center">
                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($arte['titulo']); ?></h5>
                    <p class="card-text">
                        <strong><?php echo htmlspecialchars($arte['nombre_usuario']); ?></strong> |
                        <span class="text-muted"><?php echo htmlspecialchars($arte['nombre_estilo']); ?></span> |
                        <span class="text-muted"><?php echo htmlspecialchars($arte['nombre_ilustracion']); ?></span> |
                        <span class="text-muted"><?php echo htmlspecialchars($arte['nombre_coloreado']); ?></span>
                    </p>
                </div>
            </div>
        </a>
    </div>
<?php endforeach;

$html = ob_get_clean();

$ids = ($modo === 'aleatorio')
    ? array_column($dibujos, 'idDibujo')
    : [];

echo json_encode([
    'html' => $html,
    'terminado' => count($dibujos) < $limite,
    'ids' => $ids
]);
