<?php
session_start();

require_once '../conexion.php';
require_once '../controladores/subirServicioController.php';

$subirController = new SubirServicioController($conexion);

// ID del usuario logueado
$idUsuario = $_SESSION['usuario_id'] ?? null;

if (!$idUsuario) {
    header("Location: loginVista.php");
    exit();
}

$accion = $_GET['accion'] ?? null;

if ($accion === 'guardar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $subirController->guardarServicio($idUsuario);
} else {
    $subirController->mostrarFormulario();
}