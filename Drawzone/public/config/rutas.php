<?php
session_start(); // AÚN NO IMPLEMENTADO

return [
    'home' => '../app/controllers/controllerHome.php',
    'registro' => '../app/controllers/controllerRegistro.php',
    'activarCuenta' => '../app/controllers/controllerActivacionCuenta.php',
    'login' => '../app/controllers/controllerLogin.php',
    'perfil' => isset($_SESSION['user']) ? '../app/controllers/controllerPerfil.php' : '../app/views/login.php',
    'comisiones' => isset($_SESSION['user']) && ($_SESSION['user']['rol'] === 'artista' || $_SESSION['user']['rol'] === 'ambos') 
                    ? '../app/controllers/controllerComisiones.php' 
                    : '../app/views/error_permisos.php',
    'mensajes' => isset($_SESSION['user']) ? '../app/controllers/controllerMensajes.php' : '../app/views/error_permisos.php',
    'admin' => isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin' 
               ? '../app/controllers/controllerAdmin.php' 
               : '../app/views/error_permisos.php'
];
  