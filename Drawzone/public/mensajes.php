<?php
session_start();
require_once __DIR__ . '/app/models/Usuario.php';
include 'app/views/partials/header_usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$usuarioModel = new Usuario();
$idUsuario = $_SESSION['user']['id'];

$conversaciones = $usuarioModel->obtenerConversaciones($idUsuario);

$idConversacionSeleccionada = $_GET['id'] ?? null;
$mensajes = [];
$receptor = null;

if ($idConversacionSeleccionada) {
    $mensajes = $usuarioModel->listarMensajes($idConversacionSeleccionada);
    $usuarioModel->marcarMensajesComoLeidos($idConversacionSeleccionada, $idUsuario);


    foreach ($conversaciones as $c) {
        if ($c['idConversacion'] == $idConversacionSeleccionada) {
            $receptor = $c;
            break;
        }
    }
}
?>

<div class="container my-4">
    <h2 class="text-center mb-4">Mensajes</h2>
    <div class="row">
        <!-- Conversaciones -->
        <div class="col-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Conversaciones</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaConversacion">
                    Nuevo Mensaje
                </button>
            </div>
            <div class="list-group">
                <?php foreach ($conversaciones as $conv): ?>
                    <a href="?id=<?= $conv['idConversacion'] ?>"
                       class="list-group-item list-group-item-action <?= ($idConversacionSeleccionada == $conv['idConversacion']) ? 'active' : '' ?>">
                        <div class="d-flex align-items-center">
                            <img src="<?= htmlspecialchars($conv['imagen_perfil']) ?>" class="rounded-circle me-2" width="35" height="35">
                            <div>
                                <div><?= htmlspecialchars($conv['usuario']) ?></div>
                                <small class="text-muted d-block text-truncate" style="max-width: 200px;">
                                    <?= htmlspecialchars($conv['ultimo_mensaje']) ?>
                                </small>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Mensajes -->
        <div class="col-md-8">
            <?php if ($idConversacionSeleccionada && $receptor): ?>
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <img src="<?= htmlspecialchars($receptor['imagen_perfil']) ?>" class="rounded-circle me-2" width="40" height="40">
                        <strong><?= htmlspecialchars($receptor['usuario']) ?></strong>
                    </div>
                    <div id="contenedorMensajes" class="card-body" style="height: 400px; overflow-y: auto;">

                        <?php foreach ($mensajes as $msg): ?>
                            <div class="mb-3 <?= $msg['idRemitente'] == $idUsuario ? 'text-end' : 'text-start' ?>">
                                <div class="p-2 rounded"
                                     style="display: inline-block; max-width: 70%; background-color: <?= $msg['idRemitente'] == $idUsuario ? '#A8E6CF' : '#FFD3B6' ?>;">
                                    <?= nl2br(htmlspecialchars($msg['contenido'])) ?>
                                </div>
                                <div class="small text-muted mt-1"><?= date("h:i A", strtotime($msg['fecha_envio'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer">
                        <form id="formMensaje">
                            <div class="input-group">
                                <input type="hidden" name="idConversacion" value="<?= $idConversacionSeleccionada ?>">
                                <input type="text" name="contenido" class="form-control" placeholder="Escribe un mensaje..." required>
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-muted text-center">Selecciona una conversación para comenzar a chatear.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal: Nueva Conversación -->
<div class="modal fade" id="modalNuevaConversacion" tabindex="-1" aria-labelledby="modalNuevaConversacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="app/handlers/crear_conversacion.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaConversacionLabel">Iniciar nueva conversación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <label for="usuarioDestino" class="form-label">Nombre del usuario</label>
                <input type="text" name="usuarioDestino" class="form-control" placeholder="Ej. GoldenMeow" required>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Iniciar</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="/DrawZone/public/js/mensajes/mensajes.js"></script>
<?php include 'app/views/partials/footer.php'; ?>
