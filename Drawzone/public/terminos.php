<?php
session_start();

// Determinar qué header incluir según si hay sesión iniciada
if (isset($_SESSION['user'])) {
    include 'app/views/partials/header_usuario.php';
} else {
    include 'app/views/partials/header.php';
}
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Términos y Condiciones</h2>

    <div class="card shadow p-4">
        <h4>1. Introducción</h4>
        <p>Bienvenido a DrawZone. Al utilizar nuestros servicios, aceptas estos términos y condiciones en su totalidad. Si no estás de acuerdo con alguna parte, te pedimos que no uses nuestra plataforma.</p>

        <h4>2. Registro y Cuenta</h4>
        <p>Para acceder a ciertas funciones, debes registrarte y proporcionar información precisa. Eres responsable de mantener la seguridad de tu cuenta.</p>

        <h4>3. Contenido y Propiedad Intelectual</h4>
        <p>Todo el contenido subido a DrawZone por los usuarios es responsabilidad de quien lo publica. No permitimos contenido que infrinja derechos de autor o normativas legales.</p>

        <h4>4. Comisiones y Pagos</h4>
        <p>Los pagos por comisiones se gestionan entre compradores y artistas. DrawZone no se hace responsable por acuerdos externos ni disputas de pagos.</p>

        <h4>5. Prohibiciones</h4>
        <p>Está prohibido publicar contenido NSFW sin marcarlo adecuadamente, compartir material ofensivo o realizar actividades fraudulentas dentro de la plataforma.</p>

        <h4>6. Modificaciones de los Términos</h4>
        <p>DrawZone se reserva el derecho de modificar estos términos en cualquier momento. Se notificará a los usuarios sobre cambios importantes.</p>

        <h4>7. Contacto</h4>
        <p>Si tienes dudas sobre estos términos, contáctanos a través de nuestro correo: <a href="mailto:soporte@drawzone.com">soporte@drawzone.com</a></p>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
