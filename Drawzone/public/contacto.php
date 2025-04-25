<?php
session_start();

if (isset($_SESSION['user'])) {
    include 'app/views/partials/header_usuario.php';
} else {
    include 'app/views/partials/header.php';
}
?>

<div class="container my-5">
    <h2 class="text-center mb-4">📮 ¡Contáctanos en DrawZone!</h2>

    <div class="card shadow-lg p-4 border-0">
        <div class="text-center mb-4">
            <p class="lead">¿Tienes preguntas? ¿Ideas brillantes? ¿Un meme que quieres compartir?<br>
            ¡Nos encantaría saber de ti!</p>
        </div>

        <div class="row text-center gy-5">
            <div class="col-md-4">
                <i class="bi bi-envelope-paper-fill fs-1 text-primary"></i>
                <h5 class="mt-3">Correo directo</h5>
                <p>Escríbenos a<br><a href="mailto:soporte@drawzone.com">soporte@drawzone.com</a><br>y te responderemos lo antes posible 📨</p>
            </div>

            <div class="col-md-4">
                <i class="bi bi-discord fs-1 text-indigo"></i>
                <h5 class="mt-3">Servidor de Discord</h5>
                <p>Únete a nuestra comunidad creativa<br><strong>#DrawZoneCommunity</strong><br>donde artistas y compradores conectan ✨</p>
            </div>

            <div class="col-md-4">
                <i class="bi bi-instagram fs-1 text-danger"></i>
                <h5 class="mt-3">Redes sociales</h5>
                <p>Estamos en <strong>Instagram</strong>, <strong>X</strong> y más.<br>Síguenos en <br><a href="#">@DrawZoneArt</a> para no perderte ninguna novedad 🎨</p>
            </div>
        </div>

        <hr class="my-5">

        <div class="text-center">
            <h4>¿Eres nuevo por aquí?</h4>
            <p>Consulta nuestras <a href="privacidad.php">Políticas de Privacidad</a> y <a href="terminos.php">Términos y Condiciones</a> para saber cómo cuidamos de ti 💖</p>
        </div>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
