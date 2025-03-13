<div class="offcanvas offcanvas-start" tabindex="-1" id="filtroOffcanvas" aria-labelledby="filtroOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="filtroOffcanvasLabel">Filtros de Búsqueda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form>
            <!-- Filtrar por Tipo de Usuario -->
            <div class="mb-3">
                <label class="form-label">Tipo de Usuario:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="artistasCheckbox">
                    <label class="form-check-label" for="artistasCheckbox">Artistas</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="compradoresCheckbox">
                    <label class="form-check-label" for="compradoresCheckbox">Compradores</label>
                </div>
            </div>

            <!-- Filtrar por Estilo de Arte (Checkboxes) -->
            <div class="mb-3">
                <label class="form-label">Estilo de Arte:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="manga">
                    <label class="form-check-label" for="manga">Manga</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cartoon">
                    <label class="form-check-label" for="cartoon">Cartoon</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="realismo">
                    <label class="form-check-label" for="realismo">Realismo</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="pixelart">
                    <label class="form-check-label" for="pixelart">Pixel Art</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="sketch">
                    <label class="form-check-label" for="sketch">Sketch</label>
                </div>
            </div>

            <!-- Filtrar por Tipo de Ilustración (Checkboxes) -->
            <div class="mb-3">
                <label class="form-label">Tipo de Ilustración:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="fullbody">
                    <label class="form-check-label" for="fullbody">Full Body</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="halfbody">
                    <label class="form-check-label" for="halfbody">Half Body</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="icon">
                    <label class="form-check-label" for="icon">Icon</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="comic">
                    <label class="form-check-label" for="comic">Comic</label>
                </div>
            </div>

            <!-- Rango de precios (0 - 800+) -->
            <div class="mb-3">
                <label for="precioRange" class="form-label">Rango de Precios ($USD):</label>
                <input type="range" class="form-range" id="precioRange" min="0" max="800" step="10">
                <p id="priceLabel">$0 - $800+</p>
            </div>

            <button type="button" class="btn btn-primary w-100">Aplicar Filtros</button>
        </form>
    </div>
</div>

<script>
    // Mostrar precio en tiempo real
    document.getElementById("precioRange").addEventListener("input", function() {
        let value = this.value;
        if (value >= 800) {
            document.getElementById("priceLabel").textContent = "$800 o más";
        } else {
            document.getElementById("priceLabel").textContent = "$0 - $" + value;
        }
    });
</script>
