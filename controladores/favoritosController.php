<?php
// controladores/favoritosController.php
// Maneja agregar y quitar servicios de favoritos.

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../controladores/proteger.php';

$usuario_id  = (int) ($_SESSION['usuario_id'] ?? 0);
$servicio_id = (int) ($_POST['servicio_id']   ?? 0);
$accion      = $_POST['accion']               ?? '';  // 'agregar' | 'quitar'

if (!$usuario_id || !$servicio_id || !in_array($accion, ['agregar', 'quitar'], true)) {
    header('Location: ../vista/home.php');
    exit;
}

if ($accion === 'agregar') {
    // INSERT IGNORE evita duplicados si el UNIQUE KEY ya existe
    $stmt = $conexion->prepare(
        "INSERT IGNORE INTO favoritos (usuario_id, servicio_id, fecha_agregado)
         VALUES (?, ?, NOW())"
    );
    $stmt->execute([$usuario_id, $servicio_id]);
} elseif ($accion === 'quitar') {
    $stmt = $conexion->prepare(
        "DELETE FROM favoritos WHERE usuario_id = ? AND servicio_id = ?"
    );
    $stmt->execute([$usuario_id, $servicio_id]);
}

// Redirigir de vuelta a la página del servicio
header("Location: ../vista/servicio.php?id={$servicio_id}&favorito={$accion}");
exit;