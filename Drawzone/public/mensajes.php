<?php
session_start();
include 'app/views/partials/header_usuario.php';

// Simulación de conversaciones
$conversaciones = [
    [
        "user" => "PixelNeko",
        "profile_img" => "/DrawZone/public/img/PixelNeko.jpg",
        "last_message" => "¡Gracias por la ilustración! 🎨✨",
        "messages" => [
            ["remitente" => "PixelNeko", "contenido" => "¡Hola! ¿Sigues disponible para comisiones?", "hora" => "10:00 AM"],
            ["remitente" => "yo", "contenido" => "¡Sí! ¿Qué necesitas?", "hora" => "10:05 AM"],
            ["remitente" => "PixelNeko", "contenido" => "Un retrato estilo anime, ¿puedes hacerlo?", "hora" => "10:10 AM"]
        ]
    ],
    [
        "user" => "DarkWolf99",
        "profile_img" => "/DrawZone/public/img/DarkWolf.jpg",
        "last_message" => "¿Me puedes enviar un avance?",
        "messages" => [
            ["remitente" => "DarkWolf99", "contenido" => "Hola, ¿cómo va mi comisión? 😊", "hora" => "Ayer"],
            ["remitente" => "yo", "contenido" => "Voy avanzando, te enviaré un preview pronto.", "hora" => "Hoy"]
        ]
    ]
];

?>

<div class="container my-4">
    <h2 class="text-center">Mensajes</h2>

    <div class="row">
        <!-- Lista de chats -->
        <div class="col-md-4">
            <div class="list-group">
                <?php foreach ($conversaciones as $chat): ?>
                    <a href="?chat=<?php echo $chat['user']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo $chat['profile_img']; ?>" class="rounded-circle me-2" width="40" height="40">
                            <div>
                                <strong><?php echo $chat['user']; ?></strong>
                                <p class="text-muted small mb-0"><?php echo $chat['last_message']; ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Ventana de chat -->
        <div class="col-md-8">
            <?php 
            $selectedChat = $_GET['chat'] ?? null;
            $chatData = null;
            foreach ($conversaciones as $chat) {
                if ($chat['user'] === $selectedChat) {
                    $chatData = $chat;
                    break;
                }
            }
            ?>

            <?php if ($chatData): ?>
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo $chatData['user']; ?></h5>
                    </div>
                    <div class="card-body chat-box" style="height: 400px; overflow-y: auto;">
                        <?php foreach ($chatData['messages'] as $mensaje): ?>
                            <div class="mb-3 <?php echo $mensaje['remitente'] === 'yo' ? 'text-end' : 'text-start'; ?>">
                                <div class="p-2 rounded" style="display: inline-block; background-color: <?php echo $mensaje['remitente'] === 'yo' ? '#A8E6CF' : '#FFD3B6'; ?>;">
                                    <?php echo $mensaje['contenido']; ?>
                                </div>
                                <div class="small text-muted"><?php echo $mensaje['hora']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer">
                        <form method="POST">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Escribe un mensaje...">
                                <button class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-muted text-center mt-3">Selecciona un chat para ver la conversación.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
