<?php
session_start(); 
if (isset($_SESSION['user'])) {
    include 'app/views/partials/header_usuario.php'; 
} else {
    include 'app/views/partials/header.php';
}

require_once __DIR__ . '/app/models/Usuario.php';
$usuarioModel = new Usuario();

//Capturar filtros
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : null;
$estilos = $_GET['estilos'] ?? [];
$ilustraciones = $_GET['ilustraciones'] ?? [];
$coloreados = $_GET['coloreados'] ?? [];
$tipo_usuario = $_GET['tipo_usuario'] ?? [];
$precio_min = isset($_GET['precio_min']) && trim($_GET['precio_min']) !== '' ? floatval($_GET['precio_min']) : null;
$precio_max = isset($_GET['precio_max']) && trim($_GET['precio_max']) !== '' ? floatval($_GET['precio_max']) : null;

error_log("🧪 GET[precio_min]: " . (isset($_GET['precio_min']) ? var_export($_GET['precio_min'], true) : 'NO DEFINIDO'));
error_log("🧪 GET[precio_max]: " . (isset($_GET['precio_max']) ? var_export($_GET['precio_max'], true) : 'NO DEFINIDO'));
error_log("💸 precio_min procesado: " . var_export($precio_min, true));
error_log("💸 precio_max procesado: " . var_export($precio_max, true));

$hayFiltrosActivos = !empty($busqueda) || !empty($estilos) || !empty($ilustraciones) || !empty($coloreados) || $precio_min !== null || $precio_max !== null;

if (!empty($tipo_usuario)) {
    $galeria = $usuarioModel->buscarUsuariosPorFiltrosAvanzados([
        'busqueda' => $busqueda,
        'tipo_usuario' => $tipo_usuario,
        'estilos' => $estilos,
        'ilustraciones' => $ilustraciones,
        'coloreados' => $coloreados,
        'precio_min' => $precio_min,
        'precio_max' => $precio_max,
        'offset' => 0,
        'limite' => 400
    ]);
    $esBusquedaUsuarios = true;
} elseif ($hayFiltrosActivos) {
    $galeria = $usuarioModel->buscarDibujosPorFiltrosAvanzados([
        'busqueda' => $busqueda,
        'estilos' => $estilos,
        'ilustraciones' => $ilustraciones,
        'coloreados' => $coloreados,
        'precio_min' => $precio_min,
        'precio_max' => $precio_max,
        'offset' => 0,
        'limite' => 400
    ]);
    $esBusquedaUsuarios = false;
} else {
    $galeria = $usuarioModel->obtenerGaleriaDestacada(9, 0);
    $esBusquedaUsuarios = false;
}
?>

<!-- Barra de búsqueda -->
<section class="busqueda py-3">
    <div class="container">
        <form class="d-flex justify-content-center" method="GET" action="index.php">
            <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtroOffcanvas">
                <i class="bi bi-funnel"></i> Filtros
            </button>
            <input name="busqueda" class="form-control me-2" type="search" placeholder="Buscar artistas, estilos, etc..." style="max-width: 400px;">
            <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
</section>

<?php include 'app/views/partials/filtros.php'; ?>

<!-- Galería -->
<section class="container my-5">
    <h2 class="text-center mb-4">
        <?php echo $esBusquedaUsuarios ? 'Perfiles Encontrados' : ($hayFiltrosActivos ? 'Resultados de Búsqueda' : 'Arte Destacado'); ?>
    </h2>

    <div id="galeria-container" class="row row-cols-1 row-cols-md-3 g-4">
        <?php if (!empty($galeria)): ?>
            <?php if ($esBusquedaUsuarios): ?>
                <?php foreach ($galeria as $usuario): ?>
                    <div class="col">
                        <?php
                            $linkPerfil = ($usuario['rol'] === 'comprador') 
                                ? "perfilComprador.php?id={$usuario['idUsuario']}" 
                                : "perfil.php?id={$usuario['idUsuario']}";
                        ?>
                        <a href="<?php echo $linkPerfil; ?>" class="text-decoration-none">

                            <div class="card shadow-sm text-center">
                                <img src="<?php echo htmlspecialchars($usuario['imagen_perfil'] ?? 'img/default.png'); ?>" class="card-img-top rounded-circle mx-auto mt-3" style="width: 120px; height: 120px; object-fit: cover;" alt="Perfil">
                                <div class="card-body">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($usuario['usuario']); ?></h5>
                                    <p class="text-muted">Haz clic para ver su perfil</p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($galeria as $arte): ?>
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
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-muted text-center">
                <?php echo $esBusquedaUsuarios ? 'No se encontraron perfiles.' : 'No se encontraron dibujos.'; ?>
            </p>
        <?php endif; ?>
    </div>

    <div id="loading" class="text-center my-4" style="display: none;">
        <div class="spinner-border text-dark" role="status"><span class="visually-hidden">Cargando...</span></div>
    </div>
</section>

<?php include 'app/views/partials/footer.php'; ?>

<script src="/DrawZone/public/js/jquery-3.7.1.min.js"></script>
<?php if ($hayFiltrosActivos || !empty($tipo_usuario)): ?>
    <script src="/DrawZone/public/js/indexGaleriaFiltros.js"></script>
<?php else: ?>
    <script src="/DrawZone/public/js/indexGaleria.js"></script>
<?php endif; ?>

<script>
    window.initialOffset = <?php echo isset($galeria) ? count($galeria) : 0; ?>;
    window.modoBusquedaDesdePHP = <?php echo ($hayFiltrosActivos || !empty($tipo_usuario)) ? 'true' : 'false'; ?>;

    console.log("🧠 PHP dice: modoBusquedaDesdePHP =", window.modoBusquedaDesdePHP);
</script>