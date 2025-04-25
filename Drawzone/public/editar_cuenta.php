<?php
session_start();
require_once __DIR__ . '/app/models/Usuario.php';
include 'app/views/partials/header_usuario.php';

if (!isset($_SESSION['user'])) {
    header("Location: /DrawZone/public/login.php");
    exit();
}

$usuarioModel = new Usuario();
$idUsuario = $_SESSION['user']['id'];

$redesSociales = $usuarioModel->obtenerRedesParaActualizar($idUsuario);
$tiposRed = $usuarioModel->obtenerTiposRedes();

$tiposYaUsados = array_column($redesSociales, 'idTipoRed');
$tiposDisponibles = array_filter($tiposRed, function ($tipo) use ($tiposYaUsados) {
    return !in_array($tipo['idTipoRed'], $tiposYaUsados);
});


?>

<div class="container my-5">
    <h2 class="text-center mb-4">Editar Redes</h2>

    <div class="card shadow p-4">
        <h4 class="mb-3">Tus Redes Sociales</h4>

        <?php if (!empty($redesSociales)): ?>
            <form action="app/handlers/actualizar_redes.php" method="POST">
                <?php foreach ($redesSociales as $red): ?>
                    <?php 
                        $inputName = "redes[" . htmlspecialchars($red['idTipoRed']) . "]";
                        $link = htmlspecialchars($red['link_redSocial']);
                        $nombreRed = htmlspecialchars($red['nombre_red']);
                        $tipoRed = strtolower($red['nombre_red']);
                    ?>
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-2 col-form-label"><?php echo $nombreRed; ?>:</label>
                        <div class="col-sm-8">
                            <input 
                                type="url" 
                                class="form-control red-social-input" 
                                name="<?php echo $inputName; ?>" 
                                value="<?php echo $link; ?>" 
                                placeholder="https://..."
                                data-tipo="<?php echo $tipoRed; ?>"
                                required>
                        </div>
                        <div class="col-sm-2 text-end">
                            <a href="app/handlers/eliminar_red_social.php?id=<?php echo (int)$red['id_redSocial']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar esta red social?')">
                                Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </form>
        <?php else: ?>
            <p class="text-muted">No tienes redes añadidas aún.</p>
        <?php endif; ?>
    </div>

    <div class="card shadow p-4 mt-4">
        <h4 class="mb-3">Añadir Nueva Red Social</h4>
        <form action="app/handlers/agregar_red_social.php" method="POST">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select name="idTipoRed" class="form-select" required>
                        <option value="" selected disabled>Selecciona una red</option>
                        <?php foreach ($tiposDisponibles as $tipo): ?>
                            <option value="<?php echo (int)$tipo['idTipoRed']; ?>">
                                <?php echo htmlspecialchars($tipo['nombre_red']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <input 
                        type="url" 
                        name="link_redSocial" 
                        class="form-control red-social-input" 
                        placeholder="https://..." 
                        required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Añadir</button>
                </div>
            </div>
        </form>
    </div>


<div class="text-center mt-5">
    <a href="perfil.php" class="btn btn-outline-secondary">
        ⬅️ Volver a mi Perfil
    </a>
</div>





</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const patrones = {
        twitter: /^https?:\/\/(www\.)?x\.com\/[A-Za-z0-9_]+$/,
        instagram: /^https?:\/\/(www\.)?instagram\.com\/[A-Za-z0-9_.-]+(\/\?hl=\w{2})?$/,
        facebook: /^https?:\/\/(www\.)?facebook\.com\/(profile\.php\?id=\d+|[A-Za-z0-9_.-]+)$/
    };

    const inputs = document.querySelectorAll(".red-social-input");

    inputs.forEach(input => {
        input.addEventListener("input", function () {
            const tipo = input.dataset.tipo;
            const regex = patrones[tipo];

            if (!regex) return;

            const valor = input.value.trim();

            if (valor === "" || regex.test(valor)) {
                input.classList.remove("is-invalid");
            } else {
                input.classList.add("is-invalid");
            }
        });
    });
});
</script>
