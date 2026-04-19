<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../vista/loginVista.php?server_msg=" . urlencode("Debes iniciar sesión para acceder a esta página.") . "&creacion=error");    exit();
}
?>