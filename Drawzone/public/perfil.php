<?php 
session_start();
require_once __DIR__ . '/app/models/Usuario.php';
include 'app/views/partials/header_usuario.php';

// Redirigir si no está logueado
if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioModel = new Usuario();
$idUsuario = $_SESSION['user']['id'];

// Obtener datos del usuario
$perfil = $usuarioModel->obtenerPerfil($idUsuario);
$galeria = $usuarioModel->obtenerGaleria($idUsuario);
$publicaciones = $usuarioModel->obtenerPublicaciones($idUsuario);
$redesSociales = $usuarioModel->obtenerRedesSociales($idUsuario);
$comisiones = $usuarioModel->obtenerColaComisiones($idUsuario);
?>

<!-- Contenedor Principal -->
<div class="container my-5">
    <div class="row">
        <!-- Información del Perfil -->
        <div class="col-md-4 text-center">
            <img src="<?php echo $_SESSION['user']['profile_img']; ?>" class="rounded-circle img-fluid" width="150" height="150" alt="Foto de perfil">
            <h2 class="mt-3"><?php echo $_SESSION['user']['name']; ?></h2>

            <!-- Redes Sociales -->
            <div class="d-flex justify-content-center">
                <?php if (!empty($redesSociales)): ?>
                    <?php foreach ($redesSociales as $red): ?>
                        <a href="<?php echo htmlspecialchars($red['link_redSocial']); ?>" class="btn btn-outline-dark mx-1" target="_blank">
                            <?php echo htmlspecialchars($red['nombre_red']); ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No has añadido redes sociales.</p>
                <?php endif; ?>
            </div>

            <!-- Opciones de Edición -->
            <div class="d-flex justify-content-center mt-3">
                <a href="editar_perfil.php" class="btn btn-primary me-2">Editar Perfil</a>
                <a href="configuracion.php" class="btn btn-danger">Configuración</a>
            </div>

            <!-- Términos de Servicio (ToS) -->
            <div class="mt-4">
                <h5>Términos de Servicio</h5>
                <p class="text-muted"><?php echo !empty($perfil['tos']) ? htmlspecialchars($perfil['tos']) : "Añade tus términos de servicio."; ?></p>
            </div>
        </div>

        <!-- Galería de Arte -->
        <div class="col-md-8">
            <h3 class="mb-3">Galería de Arte</h3>
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <?php if (!empty($galeria)): ?>
                    <?php foreach ($galeria as $arte): ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <img src="<?php echo htmlspecialchars($arte['link_dibujo']); ?>" class="card-img-top" alt="Arte">
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">Añade tus ejemplos de arte.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<hr>

<!-- Cola de Comisiones -->
<h3 class="mt-4 text-center">Cola de Comisiones</h3>
<div class="container d-flex justify-content-center">
    <div style="max-width: 600px; width: 100%;"> <!-- Ajusta el ancho de la tabla -->
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($comisiones)): ?>
                    <?php foreach ($comisiones as $comision): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($comision['cliente']); ?></td>
                            <td><?php echo ($comision['estado'] == 1) ? 'En cola' : 'No visible'; ?></td>
                            <td><?php echo htmlspecialchars($comision['fecha_creacion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-muted text-center">No tienes comisiones en cola.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<hr>


<!-- Publicaciones del Muro -->
<h3 class="mt-4 text-center">Muro del Artista</h3>
<div class="container">
    <div class="row">
        <?php if (!empty($publicaciones)): ?>
            <?php foreach ($publicaciones as $post): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm p-3 mb-3">
                        <p><?php echo htmlspecialchars($post['contenido']); ?></p>
                        <?php if (!empty($post['imagen'])): ?>
                            <img src="<?php echo htmlspecialchars($post['imagen']); ?>" class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">Aún no has publicado nada en tu muro.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
