<?php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión actual

// Redirige al usuario al index normal (no logueado)
header("Location: /DrawZone/public/index.php");
exit;