<?php
// vista/perfil.php
// Punto de entrada del perfil.
// Sigue el mismo patrón que home.php:
//   - incluye conexion.php desde la raíz (../)
//   - incluye el controlador
//   - llama al método correspondiente

require_once '../conexion.php';
require_once '../controladores/perfilController.php';

$perfilController = new PerfilController($conexion);

// Por ahora id=1 (usuario de prueba).
// Cuando tengas sesiones activas cámbialo por: $_SESSION['id']
$idUsuario = 1;

$accion = $_GET['accion'] ?? null;

if ($accion === 'eliminarFavorito' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $perfilController->eliminarFavorito($idUsuario);
} else {
    $perfilController->mostrarPerfil($idUsuario);
}
