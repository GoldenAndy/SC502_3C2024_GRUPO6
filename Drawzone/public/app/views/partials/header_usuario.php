<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DrawZone - Comisiones Artísticas</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/DrawZone/public/css/bootstrap.min.css">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/DrawZone/public/css/estilos.css">
</head>
<body>

<!-- Encabezado -->
<header class="main-header py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a href="/DrawZone/public/index.php" class="logo text-decoration-none">
            <h1 class="m-0">DrawZone</h1>
        </a>

        <!-- Menú de navegación -->
        <nav class="navbar navbar-expand-lg">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a href="/DrawZone/public/index.php" class="nav-link">Inicio</a></li>
                    <li class="nav-item"><a href="/DrawZone/public/explore.php" class="nav-link">Explorar</a></li>
                    <li class="nav-item"><a href="/DrawZone/public/comisiones.php" class="nav-link">Mis Comisiones</a></li>
                    <li class="nav-item"><a href="/DrawZone/public/mensajes.php" class="nav-link">Mensajes</a></li>
                    <li class="nav-item"><a href="/DrawZone/public/acerca.php" class="nav-link">Acerca de</a></li>
                </ul>
            </div>
        </nav>

        <!-- Menú de usuario loggeado -->
        <div class="user-menu">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $_SESSION['user']['profile_img'] ?? '/DrawZone/public/img/default_profile.png'; ?>" 
                         class="rounded-circle me-2" width="40" height="40" alt="Perfil">
                    <span><?php echo $_SESSION['user']['name'] ?? 'Usuario'; ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="/DrawZone/public/perfil.php">Mi Perfil</a></li>
                    <li><a class="dropdown-item" href="/DrawZone/public/pedidos.php">Mis Pedidos</a></li>
                    <li><a class="dropdown-item text-danger" href="/DrawZone/public/logout.php">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
