<?php include 'app/views/partials/header.php'; ?>

<!-- Formulario de registro -->
<main class="container py-5 d-flex justify-content-center">
    <div class="form-container p-4">
      <form class="row g-3" id="registration-form" enctype="multipart/form-data" method="post" action="/DrawZone/public/app/handlers/procesar_registro.php">

            <div class="col-md-12">
                <?php if (isset($_GET['success'])): ?>
                    <p class="text-success text-center"><?= htmlspecialchars($_GET['success']); ?></p>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <p class="text-danger text-center"><?= htmlspecialchars($_GET['error']); ?></p>
                <?php endif; ?>
            </div>

            <!-- Nombre de usuario -->
            <div class="col-md-12">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Ejemplo: ConejitaAlegre18" required>
            </div>

            <!-- Correo -->
            <div class="col-md-12">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="email" placeholder="Ingresa tu correo" required>
            </div>

            <!-- Contraseña -->
            <div class="col-md-12">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Crea una contraseña" required>
                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="col-md-12">
                <label for="confirm-password" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Repite tu contraseña" required>
            </div>

            <!-- Rol (Artista, Comprador, Ambos) -->
            <div class="col-md-12">
                <label class="form-label" for="rol">¿Eres artista o compras arte?</label> 
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rol" id="artista" value="artista" required>
                    <label class="form-check-label" for="artista">Artista</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rol" id="comprador" value="comprador" required>
                    <label class="form-check-label" for="comprador">Comprador</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rol" id="ambos" value="ambos" required>
                    <label class="form-check-label" for="ambos">Ambas</label>
                </div>
            </div>

            <!-- Redes Sociales (Solo si es Artista o Ambos) -->
            <div id="redes-sociales" class="col-md-12 d-none">
                <h5>Redes Sociales (Opcional)</h5>

                <!-- Twitter -->
                <div class="mb-3">
                    <label for="twitter" class="form-label">Twitter (X)</label>
                    <input type="url" class="form-control" id="twitter" name="twitter" placeholder="https://twitter.com/usuario">
                    <small class="text-danger d-none" id="error-twitter">Ingresa un enlace válido de Twitter.</small>
                </div>

                <!-- Instagram -->
                <div class="mb-3">
                    <label for="instagram" class="form-label">Instagram</label>
                    <input type="url" class="form-control" id="instagram" name="instagram" placeholder="https://instagram.com/usuario">
                    <small class="text-danger d-none" id="error-instagram">Ingresa un enlace válido de Instagram.</small>
                </div>

                <!-- Facebook -->
                <div class="mb-3">
                    <label for="facebook" class="form-label">Facebook</label>
                    <input type="url" class="form-control" id="facebook" name="facebook" placeholder="https://facebook.com/usuario">
                    <small class="text-danger d-none" id="error-facebook">Ingresa un enlace válido de Facebook.</small>
                </div>
            </div>


            <!-- Foto de perfil -->
            <div class="col-md-12 text-center">
                <label for="foto-perfil" class="form-label">Foto de Perfil</label>
                <input type="file" class="form-control" id="foto-perfil" name="imagen" accept="image/*">

                <!-- Contenedor centrado de previsualización -->
                <div id="preview-container">
                    <div id="preview">
                        <img id="preview-image" src="" alt="Previsualización">
                    </div>
                </div>
            </div>

            <!-- Botón de registro -->
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </div>
        </form>
    </div>
</main>

<!-- Scripts -->

<!-- Enlazar el script de previsualización -->
<script src="/DrawZone/public/js/registro/previsualizacionPFP.js"></script>
<script src="/DrawZone/public/js/registro/registroRedesSociales.js"></script>



<?php include 'app/views/partials/footer.php'; ?>
