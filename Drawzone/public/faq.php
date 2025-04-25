<?php
session_start();

// Incluir header adecuado
if (isset($_SESSION['user'])) {
    include 'app/views/partials/header_usuario.php';
} else {
    include 'app/views/partials/header.php';
}
?>

<div class="container my-5">
    <h2 class="text-center mb-4">❓ Preguntas Frecuentes (FAQ)</h2>

    <p class="lead text-center mb-5">¿Tienes dudas? ¡No estás solo! Aquí recopilamos las preguntas más comunes que recibimos en DrawZone... y sus respuestas sin rodeos 🧠✨</p>

    <div class="accordion" id="faqAccordion">

        <div class="accordion-item">
            <h2 class="accordion-header" id="faq1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true">
                    🧑‍🎨 ¿Necesito ser artista para registrarme?
                </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    ¡No! En DrawZone hay espacio tanto para artistas como para quienes buscan arte. Puedes registrarte como <strong>comprador, artista o ambos</strong>. ¡Tú eliges!
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="faq2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                    🖼️ ¿Qué tipo de contenido puedo subir?
                </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Puedes subir tus dibujos, fichas de precios, publicaciones del muro y más. Solo asegurate de respetar nuestras <a href="terminos.php">normas comunitarias</a>.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="faq3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                    💳 ¿DrawZone cobra comisión por las ventas?
                </button>
            </h2>
            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Nope. Por ahora, DrawZone es una plataforma libre de comisiones. Los pagos se acuerdan directamente entre comprador y artista. ¡Pero siempre con responsabilidad!
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="faq4">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                    🔒 ¿Mis datos están seguros?
                </button>
            </h2>
            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Absolutamente. Tu información está protegida y cifrada. No compartimos tus datos con nadie, ni siquiera el gobierno. Revisa nuestra <a href="privacidad.php">política de privacidad</a> para más info.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="faq5">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                    🧽 ¿Cómo elimino mi cuenta?
                </button>
            </h2>
            <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Puedes solicitar la eliminación total de tu cuenta escribiéndonos a <a href="mailto:soporte@drawzone.com">soporte@drawzone.com</a>. Nos pondremos emo por perderte, pero lo haremos sin problema 🥲
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-5">
        <p class="text-muted">¿Aún tienes dudas? <a href="contacto.php">Contáctanos directamente</a> y con gusto te ayudamos 💌</p>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
