<?php
function isChecked($array, $value) {
    return (!empty($_GET[$array]) && in_array($value, $_GET[$array])) ? 'checked' : '';
}
?>

<!-- FILTROS OFFCANVAS -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filtroOffcanvas" aria-labelledby="filtroOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="filtroOffcanvasLabel">Filtros de Búsqueda</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form method="GET" action="index.php">
      <!-- Tipo de Usuario -->
      <div class="mb-3">
        <label class="form-label">Tipo de Usuario:</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="tipo_usuario[]" value="artista" id="artistasCheckbox" <?= isChecked('tipo_usuario', 'artista') ?>>
          <label class="form-check-label" for="artistasCheckbox">Artistas</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="tipo_usuario[]" value="comprador" id="compradoresCheckbox" <?= isChecked('tipo_usuario', 'comprador') ?>>
          <label class="form-check-label" for="compradoresCheckbox">Compradores</label>
        </div>
      </div>

      <!-- Estilo de Arte -->
      <div class="mb-3">
        <label class="form-label">Estilo de Arte:</label>
        <?php
        $estilos = ['Anime', 'Chibi', 'Cartoon', 'Manga', 'Pixel Art', 'Realismo', 'Sketch', 'Simple', 'Otros'];
        foreach ($estilos as $estilo) {
          $id = strtolower(str_replace(' ', '', $estilo));
          echo "<div class='form-check'>
                  <input class='form-check-input' type='checkbox' name='estilos[]' value='$estilo' id='$id' " . isChecked('estilos', $estilo) . ">
                  <label class='form-check-label' for='$id'>$estilo</label>
                </div>";
        }
        ?>
      </div>

      <!-- Tipo de Ilustración -->
      <div class="mb-3">
        <label class="form-label">Tipo de Ilustración:</label>
        <?php
        $tipos = ['Full Body', 'Half Body', 'Headshot', 'Comic'];
        foreach ($tipos as $tipo) {
          $id = strtolower(str_replace(' ', '', $tipo));
          echo "<div class='form-check'>
                  <input class='form-check-input' type='checkbox' name='ilustraciones[]' value='$tipo' id='$id' " . isChecked('ilustraciones', $tipo) . ">
                  <label class='form-check-label' for='$id'>$tipo</label>
                </div>";
        }
        ?>
      </div>

      <!-- Coloreado -->
      <div class="mb-3">
        <label class="form-label">Coloreado:</label>
        <?php
        $coloreados = ['Boceto', 'Delineado', 'Color Base', 'Detallado'];
        foreach ($coloreados as $col) {
          $id = strtolower(str_replace(' ', '', $col));
          echo "<div class='form-check'>
                  <input class='form-check-input' type='checkbox' name='coloreados[]' value='$col' id='$id' " . isChecked('coloreados', $col) . ">
                  <label class='form-check-label' for='$id'>$col</label>
                </div>";
        }
        ?>
      </div>

      <!-- Rango de precios -->
      <div class="mb-3">
        <label class="form-label">Rango de Precios ($USD):</label>
        <div class="input-group">
          <span class="input-group-text">$</span>
          <input type="number" class="form-control" name="precio_min" placeholder="Mínimo"
                 value="<?= isset($_GET['precio_min']) ? htmlspecialchars($_GET['precio_min']) : '' ?>">
          <span class="input-group-text">-</span>
          <input type="number" class="form-control" name="precio_max" placeholder="Máximo"
                 value="<?= isset($_GET['precio_max']) ? htmlspecialchars($_GET['precio_max']) : '' ?>">
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Aplicar Filtros</button>
    </form>
  </div>
</div>
