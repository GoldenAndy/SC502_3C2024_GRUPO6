<?php 
session_start();
include 'app/views/partials/header.php';

// Simulación de datos de arte (esto en el futuro vendrá de la BD)
$artworks = [
    ["img" => "/DrawZone/public/img/Diluc.jpg", "title" => "Guerrero de la Llama", "artist" => "LunaDraws", "style" => "Manga", "date" => "2025-03-10"],
    ["img" => "/DrawZone/public/img/Henrietta.jpg", "title" => "Sombras del Pasado", "artist" => "PixelMaster", "style" => "Realismo", "date" => "2025-03-08"],
    ["img" => "/DrawZone/public/img/Invincible.jpg", "title" => "El Héroe", "artist" => "SakuraChan", "style" => "Cartoon", "date" => "2025-03-07"],
    ["img" => "/DrawZone/public/img/Mando.jpeg", "title" => "El Cazarrecompensas", "artist" => "ArtisticFox", "style" => "Cómic", "date" => "2025-03-05"],
    ["img" => "/DrawZone/public/img/Mario.jpg", "title" => "Super Jump!", "artist" => "MangaSoul", "style" => "Pixel Art", "date" => "2025-03-03"],
    ["img" => "/DrawZone/public/img/Purple.jpg", "title" => "Estética y Oscuridad", "artist" => "NeoRetro", "style" => "Cyberpunk", "date" => "2025-03-01"],
];

// Ordenar por fecha (recientes)
usort($artworks, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Seleccionar aleatoriamente
$random_artworks = $artworks;
shuffle($random_artworks);
?>

<div class="container my-5">
    <h2 class="text-center">Explorar Arte</h2>

    <!-- Filtros -->
    <?php include 'app/views/partials/filtros.php'; ?>

    <!-- Tabs de selección -->
    <ul class="nav nav-tabs mt-4" id="exploreTabs">
        <li class="nav-item">
            <a class="nav-link active" id="recientes-tab" data-bs-toggle="tab" href="#recientes">Recientes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="random-tab" data-bs-toggle="tab" href="#random">Aleatorio</a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Sección Recientes -->
        <div class="tab-pane fade show active" id="recientes">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($artworks as $art): ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <img src="<?php echo $art['img']; ?>" class="card-img-top" alt="<?php echo $art['title']; ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo $art['title']; ?></h5>
                                <p class="card-text"><strong><?php echo $art['artist']; ?></strong> | <span class="text-muted"><?php echo $art['style']; ?></span></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sección Aleatoria -->
        <div class="tab-pane fade" id="random">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($random_artworks as $art): ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <img src="<?php echo $art['img']; ?>" class="card-img-top" alt="<?php echo $art['title']; ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo $art['title']; ?></h5>
                                <p class="card-text"><strong><?php echo $art['artist']; ?></strong> | <span class="text-muted"><?php echo $art['style']; ?></span></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/partials/footer.php'; ?>
