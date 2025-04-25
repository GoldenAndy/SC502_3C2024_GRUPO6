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
        <h1 class="display-4 fw-bold text-gradient">Acerca de <span class="text-primary">DrawZone</span></h1>
        <p class="lead text-muted">Tu puente entre el arte y quienes lo valoran</p>
    </div>

    <section class="mb-5">
        <h3><i class="bi bi-pencil-fill me-2 text-danger"></i>¿Qué es DrawZone?</h3>
        <p class="fs-5">
            DrawZone es una plataforma vibrante que conecta artistas con amantes del arte en busca de ilustraciones personalizadas.
            Es el espacio perfecto para mostrar talento, recibir encargos y formar parte de una comunidad creativa.
        </p>
    </section>

    <section class="mb-5">
        <h3><i class="bi bi-bullseye me-2 text-warning"></i>Nuestro Objetivo</h3>
        <p class="fs-5">
            Empoderar a los artistas para que moneticen su arte, mientras ayudamos a los clientes a encontrar creaciones únicas y de alta calidad.
        </p>
    </section>

    <section class="mb-5">
        <h3><i class="bi bi-stars me-2 text-info"></i>¿Por qué usar DrawZone?</h3>
        <ul class="list-group list-group-flush fs-5">
            <li class="list-group-item"><i class="bi bi-brush me-2 text-success"></i>Arte personalizado de artistas increíbles.</li>
            <li class="list-group-item"><i class="bi bi-funnel me-2 text-secondary"></i>Filtros avanzados para encontrar tu estilo ideal.</li>
            <li class="list-group-item"><i class="bi bi-easel2 me-2 text-primary"></i>Herramientas para que los artistas gestionen sus comisiones.</li>
            <li class="list-group-item"><i class="bi bi-chat-dots me-2 text-danger"></i>Comunicación directa entre creadores y compradores.</li>
        </ul>
    </section>

    <section class="mb-5">
        <h3><i class="bi bi-people-fill me-2 text-secondary"></i>El Equipo</h3>
        <p class="fs-5">
            DrawZone nació de la colaboración entre estudiantes creativos y apasionados por el arte y la tecnología
        </p>
    </section>

    <section class="mb-5">
        <h3><i class="bi bi-lightbulb-fill me-2 text-primary"></i>Planes a Futuro</h3>
        <p class="fs-5">
            Estamos constantemente mejorando. Algunas novedades que se vienen:
        </p>
        <ul class="list-group list-group-numbered fs-5">
            <li class="list-group-item">Integración con métodos de pago seguros y rápidos.</li>
            <li class="list-group-item">Más opciones para personalizar tu perfil artístico.</li>
            <li class="list-group-item">Galerías interactivas con navegación fluida y responsiva.</li>
        </ul>
    </section>
</div>

<?php include 'app/views/partials/footer.php'; ?>
