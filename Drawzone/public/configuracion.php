<?php
session_start();
include 'app/views/partials/header_usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username'])) {
        $_SESSION['user']['name'] = htmlspecialchars($_POST['username']);
    }

    if (!empty($_POST['email'])) {
        $_SESSION['user']['email'] = htmlspecialchars($_POST['email']);
    }

    if (!empty($_FILES['profile_img']['name'])) {
        $img_name = "profile_" . time() . ".jpg";
        move_uploaded_file($_FILES['profile_img']['tmp_name'], "img/$img_name");
        $_SESSION['user']['profile_img'] = "/DrawZone/public/img/$img_name";
    }

    if (!empty($_POST['tos'])) {
        $_SESSION['user']['tos'] = htmlspecialchars($_POST['tos']);
    }

    if (!empty($_POST['color_primary'])) {
        $_SESSION['user']['colors'] = [
            "primary" => $_POST['color_primary'],
            "secondary" => $_POST['color_secondary'],
            "background" => $_POST['color_background']
        ];
    }
}
?>

<div class="main-content">

    <div class="container my-5">
        <h2 class="text-center">Configuración de Perfil</h2>

        <!-- Pestañas de Configuración -->
        <ul class="nav nav-tabs mt-4" id="configTabs">
            <li class="nav-item">
                <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info">Información de Cuenta</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="perfil-tab" data-bs-toggle="tab" href="#perfil">Personalización de Perfil</a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <!-- Información de Cuenta -->
            <div class="tab-pane fade show active" id="info">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Cambiar Imagen de Perfil</label>
                        <input type="file" class="form-control" name="profile_img" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $_SESSION['user']['name'] ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $_SESSION['user']['email'] ?? ''; ?>">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>

            <!-- Personalización de Perfil -->
            <div class="tab-pane fade" id="perfil">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Editar Términos de Servicio</label>
                        <textarea class="form-control" name="tos"><?php echo $_SESSION['user']['tos'] ?? "No hago contenido NSFW. Tiempo de entrega: 7 días."; ?></textarea>
                    </div>

                    <!-- Cambiar Colores -->
                    <h4>Paleta de Colores del Perfil</h4>
                    <div class="d-flex justify-content-center gap-3"> <!-- 📌 Mejora el espaciado -->
                        <div class="text-center">
                            <label class="form-label">Color Primario</label>
                            <input type="color" class="form-control form-control-color" name="color_primary" 
                            value="<?php echo $_SESSION['user']['colors']['primary'] ?? '#7A6753'; ?>">
                        </div>
                        <div class="text-center">
                            <label class="form-label">Color Secundario</label>
                            <input type="color" class="form-control form-control-color" name="color_secondary" 
                            value="<?php echo $_SESSION['user']['colors']['secondary'] ?? '#A39381'; ?>">
                        </div>
                        <div class="text-center">
                            <label class="form-label">Color de Fondo</label>
                            <input type="color" class="form-control form-control-color" name="color_background" 
                            value="<?php echo $_SESSION['user']['colors']['background'] ?? '#F5F3EF'; ?>">
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success">Aplicar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div> <!-- 📌 Cierre del contenedor principal -->

<!-- Aplicar los cambios de colores en tiempo real -->
<?php if (!empty($_SESSION['user']['colors'])): ?>
<style>
    :root {
        --color-primario: <?php echo $_SESSION['user']['colors']['primary']; ?>;
        --color-secundario: <?php echo $_SESSION['user']['colors']['secondary']; ?>;
        --color-fondo: <?php echo $_SESSION['user']['colors']['background']; ?>;
    }
    body {
        background-color: var(--color-fondo);
    }
    .btn-primary {
        background-color: var(--color-primario);
        border-color: var(--color-primario);
    }
    .btn-primary:hover {
        background-color: <?php echo $_SESSION['user']['colors']['primary'] ?? '#7A6753'; ?>; /* Evitar darken() */
    }
    .main-header {
        background-color: var(--color-secundario);
    }
</style>
<?php endif; ?>

<?php include 'app/views/partials/footer.php'; ?>
