<?php
// vista/subirServicio.php

require_once '../conexion.php';
require_once '../controladores/subirServicioController.php';

$subirController = new SubirServicioController($conexion);

// Por ahora id=1. Cuando tengas sesiones: $_SESSION['id']
$idUsuario = 1;

$accion = $_GET['accion'] ?? null;

if ($accion === 'guardar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $subirController->guardarServicio($idUsuario);
} else {
    $subirController->mostrarFormulario();
}

