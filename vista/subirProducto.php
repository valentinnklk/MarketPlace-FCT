<?php
// vista/subirProducto.php
// Punto de entrada para subir producto.
// Mismo patrón que home.php del proyecto.

require_once '../conexion.php';
require_once '../controladores/subirProductoController.php';

$subirController = new SubirProductoController($conexion);

// Por ahora id=1. Cuando tengas sesiones: $_SESSION['id']
$idUsuario = 1;

$accion = $_GET['accion'] ?? null;

if ($accion === 'guardar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $subirController->guardarProducto($idUsuario);
} else {
    $subirController->mostrarFormulario();
}
