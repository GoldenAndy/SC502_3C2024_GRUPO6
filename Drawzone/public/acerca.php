<?php
session_start();

// Determinar qué header incluir
if (isset($_SESSION['user'])) {
    include 'app/views/partials/header_usuario.php';
} else {
    include 'app/views/partials/header.php';
}
?>

<div class="container my-5">
    <h2 class="text-center">Acerca de DrawZone</h2>
    
    <section class="my-4">
        <h3>¿Qué es DrawZone?</h3>
        <p>
            DrawZone es una plataforma dedicada a conectar artistas con compradores de ilustraciones personalizadas.
            Aquí, los artistas pueden mostrar su trabajo, ofrecer comisiones y construir una comunidad.
        </p>
    </section>

    <section class="my-4">
        <h3>Nuestro Objetivo</h3>
        <p>
            Buscamos ofrecer un espacio donde los artistas puedan monetizar su talento, mientras los clientes encuentran ilustraciones de alta calidad y estilo único.
        </p>
    </section>

    <section class="my-4">
        <h3>¿Por qué usar DrawZone?</h3>
        <ul>
            <li>Explora y encarga arte personalizado de artistas talentosos.</li>
            <li>Usa filtros avanzados para encontrar el estilo perfecto.</li>
            <li>Los artistas pueden gestionar sus comisiones fácilmente.</li>
            <li>Facilitamos la comunicación entre compradores y creadores.</li>
        </ul>
    </section>

    <section class="my-4">
        <h3>El Equipo</h3>
        <p>
            DrawZone fue creado por un grupo de apasionados del arte y la tecnología, con el objetivo de mejorar la conexión entre artistas y clientes.
        </p>
    </section>

    <section class="my-4">
        <h3>Planes a Futuro</h3>
        <p>
            Estamos trabajando en mejorar la plataforma con más funciones, incluyendo:
        </p>
        <ul>
            <li>Sistemas de pago integrados.</li>
            <li>Mayor personalización en perfiles de artistas.</li>
            <li>Soporte para galerías interactivas.</li>
        </ul>
    </section>
</div>

<?php include 'app/views/partials/footer.php'; ?>
