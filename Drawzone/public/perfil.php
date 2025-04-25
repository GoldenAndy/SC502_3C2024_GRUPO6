<?php 
session_start();
require_once __DIR__ . '/app/models/Usuario.php';

// Redirigir si no está logueado
if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioModel = new Usuario();
$idUsuarioSesion = $_SESSION['user']['id'];
$idUsuario = $_GET['id'] ?? $idUsuarioSesion;

$isOwner = ($idUsuarioSesion == $idUsuario);

//Solo pedimos el rol si NO es el perfil propio
if (!$isOwner) {
    $rolUsuario = $usuarioModel->obtenerRolPorId($idUsuario);

    // Redirigir si ese usuario es COMPRADOR
    if ($rolUsuario === 'comprador') {
        header("Location: perfilComprador.php?id=$idUsuario");
        exit;
    }
}


$perfil         = $usuarioModel->obtenerPerfil($idUsuario);
$galeria        = $usuarioModel->obtenerGaleria($idUsuario);
$publicaciones  = $usuarioModel->obtenerPublicaciones($idUsuario);
$redesSociales  = $usuarioModel->obtenerRedesSociales($idUsuario);
$comisiones = $usuarioModel->obtenerColaComisiones($idUsuario, $isOwner);
$datosBasicos   = $usuarioModel->obtenerInfoBasicaUsuario($idUsuario);
$linkPaypal = $usuarioModel->obtenerLinkPaypal($idUsuario);
$fichasPrecios  = $usuarioModel->obtenerFichasPrecios($idUsuario);
$rangoPrecios   = $usuarioModel->obtenerRangoPrecios($idUsuario);
$reseñas = $usuarioModel->obtenerReviews($idUsuario) ?? [];

include 'app/views/partials/header_usuario.php';
?>


<!-- Contenedor Principal -->
<div class="container my-5">
    <div class="row">
        <!-- Información del Perfil -->
        <div class="col-md-4 text-center">
        <img src="<?php echo htmlspecialchars($datosBasicos['imagen_perfil']); ?>" class="rounded-circle img-fluid" width="150" height="150" alt="Foto de perfil">
        <h2 class="mt-3"><?php echo htmlspecialchars($datosBasicos['usuario']); ?></h2>


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

<!-- Paypal Link -->
<div class="mt-3">
    <?php if ($isOwner): ?>
        <?php if (!empty($linkPaypal)): ?>
          <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalConfirmarReemplazo">
            🔗 PayPal: Ya agregado
        </button>
        <?php else: ?>
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPaypal">
                ➕ Añadir PayPal
            </button>
        <?php endif; ?>
    <?php elseif (!empty($linkPaypal)): ?>
        <button class="btn btn-outline-warning" onclick="mostrarAdvertenciaPaypal('<?php echo htmlspecialchars($linkPaypal); ?>')">
            💸 Pagar por PayPal
        </button>
    <?php endif; ?>
</div>

<!-- Modal Añadir Paypal -->
<div class="modal fade" id="modalAgregarPaypal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
  <form action="app/handlers/guardar_paypal.php" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar enlace de PayPal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label for="linkPaypal" class="form-label">Tu enlace debe iniciar con:</label>
          <p class="text-muted">https://www.paypal.me/tuusuario</p>
          <input type="url"
                 class="form-control"
                 name="linkPaypal"
                 id="linkPaypal"
                 required
                 pattern="https://www\.paypal\.me\/[a-zA-Z0-9.-]+"
                 placeholder="https://www.paypal.me/--------">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Modal de Confirmación para reemplazar PayPal -->
<div class="modal fade" id="modalConfirmarReemplazo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">¿Actualizar enlace de PayPal?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>¿Deseas ingresar un nuevo enlace de PayPal? Esto reemplazará el actual. Puedes registrarlo de nuevo si lo deseas más adelante.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalAgregarPaypal">
          Sí, reemplazar
        </button>
      </div>
    </div>
  </div>
</div>


            <!-- Opciones de Edición -->
            <div class="d-flex justify-content-center mt-3">
                <?php if ($isOwner): ?>
                    <a href="editar_cuenta.php" class="btn btn-primary me-2">Editar Redes</a>
                    
                <?php endif; ?>
            </div>

<!-- Términos de Servicio (ToS) -->
<div class="mt-4">
    <h5 class="d-flex justify-content-between align-items-center">
        <span>Términos de Servicio</span>
        <?php if ($isOwner): ?>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalToS">
                Editar
            </button>
        <?php endif; ?>
    </h5>

    <p class="text-muted">
        <?php echo !empty($perfil['tos']) ? nl2br(htmlspecialchars($perfil['tos'])) : "Aún no has añadido tus Términos de Servicio."; ?>
    </p>
</div>

<!-- Modal para editar ToS -->
<div class="modal fade" id="modalToS" tabindex="-1" aria-labelledby="modalToSLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="app/handlers/actualizar_tos.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalToSLabel">Editar Términos de Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <textarea name="tos" class="form-control" rows="6" placeholder="Escribe tus términos aquí..." required><?php echo htmlspecialchars($perfil['tos'] ?? ''); ?></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar ToS</button>
            </div>
        </form>
    </div>
</div>

<!-- Rango de Precios -->
<div class="mt-4">
    <h5 class="d-flex justify-content-between align-items-center">
        <span>Rango de Precios (USD)</span>
        <?php if ($isOwner): ?>
            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRango">
                <?php echo $rangoPrecios ? 'Editar' : 'Añadir'; ?>
            </button>
        <?php endif; ?>
    </h5>

    <p class="text-muted">
        <?php if ($rangoPrecios): ?>
            Desde <strong>$<?php echo number_format($rangoPrecios['precio_minimo'], 2); ?></strong> hasta <strong>$<?php echo number_format($rangoPrecios['precio_maximo'], 2); ?></strong>
        <?php else: ?>
            Este artista aún no ha indicado su rango de precios.
        <?php endif; ?>
    </p>
</div>

<!-- Modal Rango de Precios -->
<div class="modal fade" id="modalRango" tabindex="-1" aria-labelledby="modalRangoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="app/handlers/guardar_rango.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRangoLabel">Establecer Rango de Precios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="precio_minimo" class="form-label">Precio Mínimo (USD)</label>
                    <input type="number" name="precio_minimo" id="precio_minimo" class="form-control" step="0.01" min="0"
                        value="<?php echo $rangoPrecios['precio_minimo'] ?? ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="precio_maximo" class="form-label">Precio Máximo (USD)</label>
                    <input type="number" name="precio_maximo" id="precio_maximo" class="form-control" step="0.01" min="0"
                        value="<?php echo $rangoPrecios['precio_maximo'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar Rango</button>
            </div>
        </form>
    </div>
</div>

        </div>

<!-- Galería de Arte -->
<div class="col-md-8">
    <h3 class="mb-3">Galería de Arte</h3>

    <!-- Mensajes de éxito/error -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <!-- Botón para abrir el modal -->
    <?php if ($isOwner): ?>
        <div class="mb-4 d-flex gap-2">
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalSubirDibujo">
                <strong>Añadir +</strong>
            </button>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarDibujo">
                <strong>Eliminar dibujo</strong>
            </button>
        </div>
    <?php endif; ?>


    <!-- Galería de dibujos -->
    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php if (!empty($galeria)): ?>
            <?php foreach ($galeria as $arte): ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <img src="<?php echo htmlspecialchars($arte['link_dibujo']); ?>"
                            class="card-img-top"
                            alt="Arte"
                            style="cursor:pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#modalImagen<?php echo $arte['idDibujo']; ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($arte['titulo']); ?></h5>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($arte['nombre_usuario']); ?> |
                                    <?php echo htmlspecialchars($arte['nombre_estilo']); ?> |
                                    <?php echo htmlspecialchars($arte['nombre_ilustracion']); ?> |
                                    <?php echo htmlspecialchars($arte['nombre_coloreado']); ?>
                                </small>
                            </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">Añade tus ejemplos de arte.</p>
        <?php endif; ?>
    </div>

    <!-- Modales individuales para ampliar cada imagen -->
    <?php if (!empty($galeria)): ?>
        <?php foreach ($galeria as $arte): ?>
            <div class="modal fade" id="modalImagen<?php echo $arte['idDibujo']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <img src="<?php echo htmlspecialchars($arte['link_dibujo']); ?>" class="img-fluid w-100" alt="Arte ampliado">
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Modal para subir nuevo dibujo -->
    <div class="modal fade" id="modalSubirDibujo" tabindex="-1" aria-labelledby="modalSubirDibujoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="app/handlers/subir_arte.php" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirDibujoLabel">Subir a Galería</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del Dibujo</label>
                        <input type="text" class="form-control" name="titulo" id="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="estilo" class="form-label">Estilo</label>
                        <select class="form-select" name="idEstilo" id="estilo" required>
                            <option value="">Selecciona un estilo...</option>
                            <?php 
                            $estilos = $usuarioModel->obtenerEstilos();
                            foreach ($estilos as $estilo): ?>
                                <option value="<?php echo $estilo['idEstilo']; ?>">
                                    <?php echo htmlspecialchars($estilo['nombre_estilo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ilustracion" class="form-label">Tipo de Ilustración</label>
                        <select class="form-select" name="idIlustracion" id="ilustracion" required>
                            <option value="">Selecciona un tipo...</option>
                            <?php 
                            $tiposIlustracion = $usuarioModel->obtenerTiposIlustracion();
                            foreach ($tiposIlustracion as $tipo): ?>
                                <option value="<?php echo $tipo['idIlustracion']; ?>">
                                    <?php echo htmlspecialchars($tipo['nombre_ilustracion']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="coloreado" class="form-label">Nivel de Coloreado</label>
                        <select class="form-select" name="idColoreado" id="coloreado" required>
                            <option value="">Selecciona un nivel...</option>
                            <?php 
                            $tiposColoreado = $usuarioModel->obtenerTiposColoreado();
                            foreach ($tiposColoreado as $color): ?>
                                <option value="<?php echo $color['idColoreado']; ?>">
                                    <?php echo htmlspecialchars($color['nombre_coloreado']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dibujo" class="form-label">Selecciona tu archivo</label>
                        <input type="file" name="dibujo" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Subir a Galería</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal para eliminar dibujo -->
<div class="modal fade" id="modalEliminarDibujo" tabindex="-1" aria-labelledby="modalEliminarDibujoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="app/handlers/eliminar_arte.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarDibujoLabel">Eliminar Dibujo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Selecciona el dibujo que deseas eliminar:</p>
                <select class="form-select" name="idDibujo" required>
                    <option value="">Selecciona un dibujo...</option>
                    <?php foreach ($galeria as $arte): ?>
                        <option value="<?php echo $arte['idDibujo']; ?>">
                            <?php echo htmlspecialchars($arte['titulo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <!-- Puedes agregar una vista previa si quieres -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
        </form>
    </div>
</div>



    </div>
</div>

<hr>


<!-- Fichas de Precios -->
<div class="container my-5">
    <h3 class="mb-3 text-center">Fichas de Precios</h3>

    <?php if ($isOwner): ?>
        <div class="mb-4 text-center">
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFichaPrecios">
                <strong>Subir Ficha de Precios</strong>
            </button>
        </div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php if (!empty($fichasPrecios)): ?>
            <?php foreach ($fichasPrecios as $ficha): ?>
                <div class="col">
                    <div class="card shadow-sm">
                    <img 
    src="<?php echo htmlspecialchars($ficha['link_ficha']); ?>" 
    class="card-img-top img-fluid" 
    alt="Ficha de Precios" 
    style="object-fit: contain; cursor:pointer"
    data-bs-toggle="modal"
    data-bs-target="#modalFicha<?php echo $ficha['idFicha']; ?>">

                        <?php if ($isOwner): ?>
                            <div class="card-body text-center">
                                <a href="app/handlers/editar_ficha.php?id=<?php echo $ficha['idFicha']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminarFicha<?php echo $ficha['idFicha']; ?>">
                                    Eliminar
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">No has subido ninguna ficha de precios aún.</p>
        <?php endif; ?>
    </div>


<!-- Modales para ampliar fichas -->
<?php if (!empty($fichasPrecios)): ?>
    <?php foreach ($fichasPrecios as $ficha): ?>
        <div class="modal fade" id="modalFicha<?php echo $ficha['idFicha']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <img src="<?php echo htmlspecialchars($ficha['link_ficha']); ?>" class="img-fluid w-100" alt="Ficha Ampliada">
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

  

    <!-- Modales de eliminación personalizados -->
    <?php if (!empty($fichasPrecios) && $isOwner): ?>
        <?php foreach ($fichasPrecios as $ficha): ?>
            <div class="modal fade" id="modalEliminarFicha<?php echo $ficha['idFicha']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="app/handlers/eliminar_ficha.php" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">¿Eliminar esta ficha?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="<?php echo htmlspecialchars($ficha['link_ficha']); ?>" class="img-fluid mb-3" alt="Ficha de Precios">
                            <p>¿Estás seguro de que deseas eliminar esta ficha?</p>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="idFicha" value="<?php echo $ficha['idFicha']; ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<!-- Modal para subir Ficha de Precios -->
<div class="modal fade" id="modalFichaPrecios" tabindex="-1" aria-labelledby="modalFichaPreciosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="app/handlers/subir_ficha.php" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFichaPreciosLabel">Subir Ficha de Precios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="fichas" class="form-label">Selecciona las imágenes de tus fichas</label>
                    <input type="file" name="fichas[]" id="fichas" class="form-control" accept="image/*" multiple required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Subir Fichas</button>
            </div>
        </form>
    </div>
</div>




<!-- Cola de Comisiones -->
<h3 class="mt-4 text-center">Cola de Comisiones</h3>

<div class="container d-flex flex-column align-items-center">
    <?php if ($isOwner): ?>
        <button class="btn btn-outline-secondary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevaComision">
            <strong>Añadir +</strong>
        </button>
    <?php endif; ?>

    <div style="max-width: 600px; width: 100%;">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($comisiones)): ?>
                    <?php foreach ($comisiones as $comision): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($comision['cliente']); ?></td>
                            <td>
                                <?php if ($isOwner): ?>
                                    <form action="app/handlers/actualizar_estado_comision.php" method="POST" class="d-inline">
                                        <input type="hidden" name="idComision" value="<?php echo $comision['idComision']; ?>">
                                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="0" <?php if ($comision['estado'] == 0) echo 'selected'; ?>>No visible</option>
                                            <option value="1" <?php if ($comision['estado'] == 1) echo 'selected'; ?>>Pendiente</option>
                                            <option value="2" <?php if ($comision['estado'] == 2) echo 'selected'; ?>>En proceso</option>
                                            <option value="3" <?php if ($comision['estado'] == 3) echo 'selected'; ?>>Terminado</option>
                                        </select>
                                    </form>
                                <?php else: ?>
                                    <?php 
                                        $estados = [0 => 'No visible', 1 => 'Pendiente', 2 => 'En proceso', 3 => 'Terminado'];
                                        echo $estados[$comision['estado']] ?? 'Desconocido';
                                    ?>
                                <?php endif; ?>
                            </td>
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


<!-- Modal: Nueva Comisión -->
<div class="modal fade" id="modalNuevaComision" tabindex="-1" aria-labelledby="modalNuevaComisionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="app/handlers/crear_comision.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaComisionLabel">Nueva Comisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="cliente" class="form-label">Nombre del Cliente</label>
                    <input type="text" class="form-control" name="cliente" id="cliente" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="1">Pendiente</option>
                        <option value="2">En proceso</option>
                        <option value="3">Terminado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Añadir Comisión</button>
            </div>
        </form>
    </div>
</div>

<hr>


<!-- Muro de Publicaciones -->
<h3 class="mt-4 text-center">Muro de Arte</h3>

<?php if ($isOwner): ?>
<!-- Botón para abrir el modal de nueva publicación -->
<div class="text-center my-4">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevaPublicacion">
        ➕ Añadir Publicación
    </button>
</div>

<!-- Modal Nueva Publicación -->
<div class="modal fade" id="modalNuevaPublicacion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <form action="app/handlers/nueva_publicacion.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Nueva Publicación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <textarea name="contenido" class="form-control" rows="4" placeholder="¿Qué estás pensando?..." required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Imagen (opcional):</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Publicar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="container mb-5">
  <div class="row row-cols-1 row-cols-md-4 g-3">
    <?php if (!empty($publicaciones)): ?>
      <?php foreach ($publicaciones as $post): ?>
        <div class="col">
          <div class="card shadow-sm"
               style="cursor: pointer;"
               data-id-publicacion="<?php echo $post['idPublicacion']; ?>">
            <?php if (!empty($post['imagen'])): ?>
              <img src="<?php echo htmlspecialchars($post['imagen']); ?>"
                   class="card-img-top"
                   alt="Imagen del post"
                   style="max-height: 260px; object-fit: contain;">
            <?php endif; ?>
            <div class="card-body text-center py-2 px-3">
              <p class="card-text mb-1" style="font-size: 0.95rem;"><?php echo htmlspecialchars($post['contenido']); ?></p>
              <small class="text-muted d-block mb-2">
                Publicado el <?php echo date("d M Y", strtotime($post['fecha_publicacion'])); ?>
              </small>
              <!-- Botón de like -->
              <button class="btn btn-sm btn-outline-danger" onclick="darLike(<?php echo $post['idPublicacion']; ?>, event)">
                ❤️ <span id="likeCount<?php echo $post['idPublicacion']; ?>"><?php echo $post['likes']; ?></span>
              </button>

              <?php if ($isOwner): ?>
                <div class="mt-2 d-flex justify-content-center gap-2">
                  <!-- Botón editar -->
                  <button class="btn btn-sm btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#modalEditarPost<?php echo $post['idPublicacion']; ?>">
                    ✏️ Editar
                  </button>

                  <!-- Botón eliminar (opcional, requiere handler) -->
                  <form action="app/handlers/eliminar_publicacion.php" method="POST"
                        onsubmit="return confirm('¿Seguro que deseas eliminar esta publicación?')">
                    <input type="hidden" name="idPublicacion" value="<?php echo $post['idPublicacion']; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Eliminar</button>
                  </form>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Modal para ver publicación ampliada -->
        <div class="modal fade" id="modalPost<?php echo $post['idPublicacion']; ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <?php if (!empty($post['imagen'])): ?>
                  <img src="<?php echo htmlspecialchars($post['imagen']); ?>" class="img-fluid mb-3 w-100">
                <?php endif; ?>
                <p><?php echo nl2br(htmlspecialchars($post['contenido'])); ?></p>
                <div class="text-muted small mt-2">
                  <span>Publicado el <?php echo date("d M Y", strtotime($post['fecha_publicacion'])); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal editar publicación -->
        <?php if ($isOwner): ?>
        <div class="modal fade" id="modalEditarPost<?php echo $post['idPublicacion']; ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
              <form action="app/handlers/editar_publicacion.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idPublicacion" value="<?php echo $post['idPublicacion']; ?>">
                <div class="modal-header">
                  <h5 class="modal-title">Editar Publicación</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <textarea name="contenido" class="form-control" rows="4" required><?php echo htmlspecialchars($post['contenido']); ?></textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Imagen (opcional para cambiar):</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                    <?php if (!empty($post['imagen'])): ?>
                      <img src="<?php echo htmlspecialchars($post['imagen']); ?>" class="img-fluid mt-2 rounded" style="max-height: 150px;">
                    <?php endif; ?>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php endif; ?>

      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-muted text-center">Aún no has publicado nada en tu muro.</p>
    <?php endif; ?>
  </div>
</div>


        <!-- Reseñas de Clientes -->
        <div class="container mb-5">
  <h3 class="text-center mb-4">Reseñas de Clientes</h3>
  <?php if (!empty($perfil['rol']) && $perfil['rol'] !== 'comprador'): ?>
    <div class="row row-cols-1 row-cols-md-4 g-3">
      <?php
        $reseñas = $usuarioModel->obtenerReviews($idUsuario);
        if (!empty($reseñas)):
          foreach ($reseñas as $resena):
      ?>
        <div class="col">
          <div class="card shadow-sm" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalResena<?php echo $resena['idResena']; ?>">
            <?php if (!empty($resena['imagen_resultado'])): ?>
              <img src="<?php echo htmlspecialchars($resena['imagen_resultado']); ?>" 
                   class="card-img-top" 
                   alt="Imagen reseña" 
                   style="max-height: 260px; object-fit: contain;">
            <?php endif; ?>
            <div class="card-body text-center py-2 px-3">
              <h6 class="card-title mb-2"><?php echo str_repeat("⭐", $resena['calificacion']); ?></h6>
              <p class="card-text small"><?php echo nl2br(htmlspecialchars($resena['comentario'])); ?></p>
              <small class="text-muted d-block mt-2">
                — <?php echo htmlspecialchars($resena['nombre_cliente']); ?>, el <?php echo date("d M Y", strtotime($resena['fecha_resena'])); ?>
              </small>
            </div>
          </div>
        </div>

        <!-- Modal para ver imagen ampliada -->
        <?php if (!empty($resena['imagen_resultado'])): ?>
          <div class="modal fade" id="modalResena<?php echo $resena['idResena']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-body p-0">
                  <img src="<?php echo htmlspecialchars($resena['imagen_resultado']); ?>" class="img-fluid w-100" alt="Imagen ampliada reseña">
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

      <?php endforeach; else: ?>
        <p class="text-muted text-center">Este artista aún no tiene reseñas.</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>


<?php foreach ($reseñas as $resena): ?>
  <?php if (!empty($resena['imagen_resultado'])): ?>
    <div class="modal fade" id="modalResena<?php echo $resena['idResena']; ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body p-0">
            <img src="<?php echo htmlspecialchars($resena['imagen_resultado']); ?>" class="img-fluid w-100 rounded-bottom" alt="Imagen ampliada de la reseña">
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php endforeach; ?>

<?php if (!$isOwner): ?>
  <div class="text-center mt-3 mb-5">
    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalAdvertencia">
      Dejar una Reseña
    </button>
  </div>

  <!-- Modal de Advertencia -->
  <div class="modal fade" id="modalAdvertencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Advertencia antes de continuar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p>
            Por favor, sé honesto con tu reseña. Valorar artistas sin haber trabajado con ellos puede conllevar la suspensión de tu cuenta.
          </p>
          <p>
            Las reseñas ofensivas o repetidamente inapropiadas podrían resultar en un baneo permanente.
          </p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDejarResena" data-bs-dismiss="modal">Entiendo, continuar</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>



<!-- Modal Dejar Reseña -->
<div class="modal fade" id="modalDejarResena" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="app/handlers/dejar_review.php" method="POST" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Dejar Reseña</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idArtista" value="<?php echo $idUsuario; ?>">
        <div class="mb-3">
          <label class="form-label">Calificación (1-5 estrellas)</label>
          <select class="form-select" name="calificacion" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?> ⭐</option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Comentario</label>
          <textarea name="comentario" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Imagen del resultado (opcional)</label>
          <input type="file" name="imagen_resultado" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Enviar Reseña</button>
      </div>
    </form>
  </div>
</div>




<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.card[data-id-publicacion]').forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('button')) return;

            const id = this.getAttribute('data-id-publicacion');
            const modal = new bootstrap.Modal(document.getElementById(`modalPost${id}`));
            modal.show();
        });
    });
});
</script>

<script src="js/perfil/dar_like.js"></script>

<script>
function mostrarAdvertenciaPaypal(link) {
    if (confirm("⚠️ Estás a punto de salir de DrawZone y pagar a un artista.\n\nAsegúrate de haber hablado con el artista antes de enviar dinero.\nDrawZone no se hace responsable por pagos adelantados o rechazos.\n\n¿Quieres continuar al enlace de PayPal?")) {
        window.open(link, "_blank");
    }
}
</script>

<?php include 'app/views/partials/footer.php'; ?>

