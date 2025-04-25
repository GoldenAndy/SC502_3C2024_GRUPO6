<?php 
session_start();

$logueado = isset($_SESSION['user']);

if ($logueado) {
    include 'app/views/partials/header_usuario.php'; 
} else {
    include 'app/views/partials/header.php';
}
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Explorar Arte</h2>

    <!-- Tabs de selección -->
    <ul class="nav nav-tabs mt-4" id="exploreTabs">
        <li class="nav-item">
            <a class="nav-link active" id="recientes-tab" data-bs-toggle="tab" href="#recientes" data-modo="recientes">Recientes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="random-tab" data-bs-toggle="tab" href="#aleatorio" data-modo="aleatorio">Aleatorio</a>
        </li>
    </ul>

    <!-- Contenedor de galerías -->
    <div class="tab-content mt-4">
        <div class="tab-pane fade show active" id="recientes">
            <div id="galeria-recientes" class="row row-cols-1 row-cols-md-3 g-4"></div>
            <div id="loading-recientes" class="text-center my-4" style="display: none;">
                <div class="spinner-border text-dark" role="status"><span class="visually-hidden">Cargando...</span></div>
            </div>
        </div>

        <div class="tab-pane fade" id="aleatorio">
            <div id="galeria-aleatorio" class="row row-cols-1 row-cols-md-3 g-4"></div>
            <div id="loading-aleatorio" class="text-center my-4" style="display: none;">
                <div class="spinner-border text-dark" role="status"><span class="visually-hidden">Cargando...</span></div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>

<script src="/DrawZone/public/js/jquery-3.7.1.min.js"></script>
<script src="/DrawZone/public/js/exploreGaleria.js"></script>

<script>
    // Valores iniciales por pestaña
    window.explore = {
        recientes: { offset: 0, cargando: false, terminado: false },
        aleatorio: { offset: 0, cargando: false, terminado: false }
    };
</script>