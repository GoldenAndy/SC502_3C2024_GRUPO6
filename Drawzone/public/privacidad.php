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
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary title-glow">Política de Privacidad</h1>
        <p class="text-muted fs-5">Transparencia total. Tus datos. Nuestra responsabilidad.</p>
    </div>

    <div class="glass-card">
        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-person-lines-fill"></i></span> Información que Recopilamos</h4>
            <p>
                Al unirte a <strong>DrawZone</strong>, recolectamos tu nombre de usuario, correo electrónico, una contraseña encriptada nivel NASA,
                y si querés, tu avatar artístico y tus redes sociales para conectar más allá.
            </p>
        </section>

        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-gear-wide-connected"></i></span> Uso de tus Datos</h4>
            <p>
                Tu info no es mercancía. La usamos únicamente para mejorar tu experiencia, personalizar tu navegación
                y mantener viva la conexión entre arte y artista. <strong>No vendemos. No compartimos. Nunca.</strong>
            </p>
        </section>

        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-shield-lock-fill"></i></span> Seguridad</h4>
            <p>
                Tus datos están encriptados con amor y buenas prácticas. Ni siquiera nosotros podemos ver tu contraseña (en serio, no la olvides).
                Y si algo falla, tenemos backups y sistemas de prevención que hacen llorar de orgullo a cualquier dev.
            </p>
        </section>

        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-cookie"></i></span> Cookies 🍪</h4>
            <p>
                Sí, usamos cookies. Pero no de chocolate. Sirven para mantener tu sesión viva, recordar tus preferencias y que todo cargue más rápido.
                Las podés desactivar desde tu navegador, aunque te vas a perder parte de la magia.
            </p>
        </section>

        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-image-fill"></i></span> Contenido Generado por Usuarios</h4>
            <p>
                Lo que subas es tuyo. Pero si rompes las reglas (como subir cosas ofensivas o ilegales), nos reservamos el derecho de moderar.
                Este es un espacio seguro y creativo para todos.
            </p>
        </section>

        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-unlock-fill"></i></span> Tus Derechos</h4>
            <p>
                Querés eliminar tu cuenta, cambiar datos o borrar tu existencia digital en DrawZone? Podés hacerlo desde tu perfil,
                o mandarnos un mensaje y lo hacemos por vos. Fácil y sin vueltas.
            </p>
        </section>

        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-arrow-clockwise"></i></span> Cambios en la Política</h4>
            <p>
                Esta política puede actualizarse con el tiempo. Te avisaremos de los cambios importantes para que estés siempre al tanto.
            </p>
        </section>

        <section class="section-block">
            <h4><span class="icon-badge"><i class="bi bi-envelope-paper-fill"></i></span> Contacto</h4>
            <p>
                ¿Dudas, sugerencias o solo querés saludar? Mandanos un correo a: 
                <a href="mailto:soporte@drawzone.com" class="fw-semibold text-decoration-none">soporte@drawzone.com</a>
            </p>
        </section>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
