<?php
session_start(); 
if (isset($_SESSION['user'])) {
    include 'app/views/partials/header_usuario.php'; 
} else {
    include 'app/views/partials/header.php';
}
?>


<!-- Barra de búsqueda con filtros -->
<section class="busqueda py-3">
    <div class="container">
        <form class="d-flex justify-content-center">
            <button class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtroOffcanvas" aria-controls="filtroOffcanvas">
                <i class="bi bi-funnel"></i> Filtros
            </button>
            <input class="form-control me-2" type="search" placeholder="Buscar artistas, estilos, etc..." aria-label="Buscar" style="max-width: 400px;">
            <button class="btn btn-dark" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</section>

<!-- Filtros -->
<?php include 'app/views/partials/filtros.php'; ?>

<!-- Galería de Arte Destacado -->
<section class="container my-5">
    <h2 class="text-center mb-4">Arte Destacado</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php 
        // Lista de imágenes con datos del artista
        $artworks = [
            ["img" => "/Drawzone/public/img/Diluc.jpg", "title" => "Guerrero de la Llama", "artist" => "LunaDraws", "style" => "Manga", "url" => "perfil.php?artista=LunaDraws"],
            ["img" => "/Drawzone/public/img/Henrietta.jpg", "title" => "Sombras del Pasado", "artist" => "PixelMaster", "style" => "Realismo", "url" => "perfil.php?artista=PixelMaster"],
            ["img" => "/Drawzone/public/img/Invincible.jpg", "title" => "El Héroe", "artist" => "SakuraChan", "style" => "Cartoon", "url" => "perfil.php?artista=SakuraChan"],
            ["img" => "/Drawzone/public/img/Mando.jpeg", "title" => "El Cazarrecompensas", "artist" => "ArtisticFox", "style" => "Cómic", "url" => "perfil.php?artista=ArtisticFox"],
            ["img" => "/Drawzone/public/img/Mario.jpg", "title" => "Super Jump!", "artist" => "MangaSoul", "style" => "Pixel Art", "url" => "perfil.php?artista=MangaSoul"],
            ["img" => "/Drawzone/public/img/Purple.jpg", "title" => "Estética y Oscuridad", "artist" => "NeoRetro", "style" => "Cyberpunk", "url" => "perfil.php?artista=NeoRetro"],
            ["img" => "/Drawzone/public/img/Riko_Nozomi.jpg", "title" => "Riko Nozomi", "artist" => "Mike y Maick ", "style" => "Anime", "url" => "perfil.php?artista=Mike y Maick "],
            ["img" => "/Drawzone/public/img/Sailor_Girl.jpg", "title" => "Marina Encantada", "artist" => "CyberInk", "style" => "Manga", "url" => "perfil.php?artista=CyberInk"],
            ["img" => "/Drawzone/public/img/Tartas.jpg", "title" => "Delicias en Arte", "artist" => "DreamIllustrator", "style" => "Ilustración Digital", "url" => "perfil.php?artista=DreamIllustrator"]
        ];

        foreach ($artworks as $art) {
            echo "
                <div class='col'>
                    <a href='{$art['url']}' class='text-decoration-none'>
                        <div class='card shadow-sm'>
                            <img src='{$art['img']}' class='card-img-top' alt='{$art['title']}'>
                            <div class='card-body text-center'>
                                <h5 class='card-title'>{$art['title']}</h5>
                                <p class='card-text'><strong>{$art['artist']}</strong> | <span class='text-muted'>{$art['style']}</span></p>
                            </div>
                        </div>
                    </a>
                </div>
            ";
        }
        ?>
    </div>
</section>

<?php include 'app/views/partials/footer.php'; ?>
