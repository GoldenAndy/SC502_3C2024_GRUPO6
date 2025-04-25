<?php 
session_start();
require_once __DIR__ . '/app/models/Usuario.php';
include 'app/views/partials/header_usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioModel    = new Usuario();
$idUsuarioSesion = $_SESSION['user']['id'];
$idUsuario       = $_GET['id'] ?? $idUsuarioSesion;

// Validar rol
$rol = $usuarioModel->obtenerRolUsuario($idUsuario);
if ($rol !== 'comprador' && $rol !== 'ambos') {
    header("Location: perfil.php?id=$idUsuario");
    exit();
}

// Obtener datos básicos
$perfil         = $usuarioModel->obtenerPerfil($idUsuario);
$datosBasicos   = $usuarioModel->obtenerInfoBasicaUsuario($idUsuario);
$redesSociales  = $usuarioModel->obtenerRedesSociales($idUsuario);
$isOwner        = ($idUsuarioSesion == $idUsuario);
?>

<!-- Contenedor Principal -->
<div class="container my-5">
    <div class="row">
        <!-- Información del Perfil -->
        <div class="col-md-4 text-center">
            <img src="<?php echo htmlspecialchars($datosBasicos['imagen_perfil']); ?>" 
                 class="rounded-circle img-fluid border border-2 border-secondary" width="150" height="150" 
                 alt="Foto de perfil">
            <h2 class="mt-3"><?php echo htmlspecialchars($datosBasicos['usuario']); ?></h2>

            <!-- Redes Sociales -->
            <div class="d-flex justify-content-center mt-3">
                <?php if (!empty($redesSociales)): ?>
                    <?php foreach ($redesSociales as $red): ?>
                        <a href="<?php echo htmlspecialchars($red['link_redSocial']); ?>" 
                           class="btn btn-outline-dark mx-1" target="_blank">
                            <?php echo htmlspecialchars($red['nombre_red']); ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>

        <!-- Presentación -->
        <div class="col-md-8">
            <div class="p-4 rounded shadow-sm bg-light">
                <?php if ($isOwner): ?>
                    <h2 class="display-6">¡Bienvenido, <?php echo htmlspecialchars($datosBasicos['usuario']); ?>!</h2>
                    <p class="lead">Como comprador, aquí descubrirás las últimas publicaciones y recomendaciones en arte. Explora, inspírate y encuentra esa obra perfecta que complemente tu estilo.</p>
                <?php else: ?>
                    <h2 class="display-6"><?php echo htmlspecialchars($datosBasicos['usuario']); ?> <small class="text-muted">(Comprador)</small></h2>
                    <p class="lead">Este usuario forma parte de nuestra comunidad como comprador. Podés conocer sus intereses mandándole un mensaje!</p>
                <?php endif; ?>
                <hr class="my-4">
                <p>Utiliza la barra de búsqueda o los filtros para encontrar obras por artistas, estilos y más.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
