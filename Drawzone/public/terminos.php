<?php
session_start();

if (isset($_SESSION['user'])) {
    include 'app/views/partials/header_usuario.php';
} else {
    include 'app/views/partials/header.php';
}
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary">Términos y Condiciones</h1>
        <p class="text-muted">Última actualización: abril 2025</p>
    </div>

    <div class="card shadow-lg p-4 border-0">
        <section class="mb-4">
            <h4><i class="bi bi-box-arrow-in-right text-success me-2"></i>1. Introducción</h4>
            <p>
                Bienvenido a <strong>DrawZone</strong>. Al utilizar nuestros servicios, aceptas estos términos y condiciones en su totalidad.
                Si no estás de acuerdo con alguna parte, te pedimos que no uses nuestra plataforma.
            </p>
        </section>

        <section class="mb-4">
            <h4><i class="bi bi-person-lines-fill text-primary me-2"></i>2. Registro y Cuenta</h4>
            <p>
                Para acceder a ciertas funciones, es necesario registrarte proporcionando información precisa y actualizada.
                Eres responsable de la seguridad de tu cuenta y tus credenciales.
            </p>
        </section>

        <section class="mb-4">
            <h4><i class="bi bi-file-earmark-lock text-danger me-2"></i>3. Contenido y Propiedad Intelectual</h4>
            <p>
                El contenido subido por los usuarios es responsabilidad de quien lo publica. Está prohibido compartir material que infrinja derechos de autor o leyes aplicables.
            </p>
        </section>

        <section class="mb-4">
            <h4><i class="bi bi-cash-coin text-warning me-2"></i>4. Comisiones y Pagos</h4>
            <p>
                Los pagos por comisiones se gestionan directamente entre artistas y compradores. <strong>DrawZone</strong> no interviene en acuerdos externos ni asume responsabilidad por disputas.
            </p>
        </section>

        <section class="mb-4">
            <h4><i class="bi bi-shield-exclamation text-dark me-2"></i>5. Prohibiciones</h4>
            <p>
                No está permitido publicar contenido NSFW sin marcarlo como tal, ni compartir material ofensivo o realizar actos fraudulentos en la plataforma.
            </p>
        </section>

        <section class="mb-4">
            <h4><i class="bi bi-sliders2 text-info me-2"></i>6. Modificaciones de los Términos</h4>
            <p>
                <strong>DrawZone</strong> puede actualizar estos términos en cualquier momento. Se notificará a los usuarios sobre cambios relevantes a través del sitio web.
            </p>
        </section>

        <section>
            <h4><i class="bi bi-envelope-at text-secondary me-2"></i>7. Contacto</h4>
            <p>
                ¿Tienes dudas o sugerencias? Escríbenos a: 
                <a href="mailto:soporte@drawzone.com" class="text-decoration-none fw-semibold">soporte@drawzone.com</a>
            </p>
        </section>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
