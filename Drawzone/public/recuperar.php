<?php include 'app/views/partials/header.php'; ?>

<main class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="form-container p-4 shadow-lg rounded">
        <h2 class="text-center mb-4">Recuperar Contraseña</h2>
        <p class="text-center text-muted">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>

        <!-- Formulario de recuperación -->
        <form method="POST" action="app/handlers/procesar_recuperacion.php">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Tu correo aquí" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Enviar Enlace</button>
            </div>
        </form>

        <p class="text-center mt-3">
            <a href="login.php">Volver a Iniciar Sesión</a>
        </p>
    </div>
</main>

<?php include 'app/views/partials/footer.php'; ?>
