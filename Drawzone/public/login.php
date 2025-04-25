<?php include 'app/views/partials/header.php'; ?>

<!-- Sección de Login -->
<main class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="form-container p-4 shadow-lg rounded">
        <h2 class="text-center mb-4">Iniciar Sesión</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Mensaje de error si hubo algún problema -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST" action="app/handlers/procesar_login.php">  
            <!-- Correo -->
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="email" placeholder="Ingresa tu correo" required>
            </div>

            <!-- Contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                    <button type="button" class="btn btn-outline-secondary" id="toggle-password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Botón de inicio de sesión -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>

            <!-- Enlace de recuperar contraseña -->
            <p class="text-center mt-2">
                <a href="recuperar.php" class="text-muted">¿Olvidaste tu contraseña?</a>
            </p>
        </form>

        <!-- Enlace para registrarse -->
        <p class="text-center mt-3">
            ¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a>
        </p>
    </div>
</main>

    <!-- Enlace de scripts -->

<script src="/DrawZone/public/js/registro/mostrarPassword.js"></script>

<?php include 'app/views/partials/footer.php'; ?>
