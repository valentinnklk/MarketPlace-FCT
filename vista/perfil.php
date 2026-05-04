<?php
// vista/perfil.php
// Punto de entrada del perfil. Usa el usuario logueado real ($_SESSION['usuario_id']).

session_start();
require_once "../controladores/proteger.php";
require_once '../conexion.php';
require_once '../controladores/perfilController.php';

$perfilController = new PerfilController($conexion);

$idUsuario = (int) $_SESSION['usuario_id'];

$accion = $_GET['accion'] ?? null;

if ($accion === 'eliminarFavorito' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $perfilController->eliminarFavorito($idUsuario);
} else {
    $perfilController->mostrarPerfil($idUsuario);
}
