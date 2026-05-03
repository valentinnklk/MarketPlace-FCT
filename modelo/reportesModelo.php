<?php
require_once __DIR__ . '/../conexion.php';

// Total de reportes por estado
function obtenerEstadisticasReportes() {
    global $conexion;
    $sql = "SELECT estado, COUNT(*) as total FROM reportes GROUP BY estado";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Reportes de servicios con datos del servicio
function obtenerReportesServicios() {
    global $conexion;
    $sql = "SELECT r.id, r.motivo, r.estado, r.fecha_creacion,
                   s.id as servicio_id, s.titulo
            FROM reportes r
            JOIN servicios s ON r.servicio_id = s.id
            WHERE r.tipo = 'servicio'
            ORDER BY r.fecha_creacion DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Reportes de usuarios con datos del usuario
function obtenerReportesUsuarios() {
    global $conexion;
    $sql = "SELECT r.id, r.motivo, r.estado, r.fecha_creacion,
                   u.id as usuario_id, u.nombre as usuario_nombre
            FROM reportes r
            JOIN usuarios u ON r.usuario_reportado_id = u.id
            WHERE r.tipo = 'usuario'
            ORDER BY r.fecha_creacion DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function insertarReporte($reportador_id, $tipo, $servicio_id, $usuario_reportado_id, $motivo) {
    global $conexion;
    $sql = "INSERT INTO reportes (reportador_id, tipo, servicio_id, usuario_reportado_id, motivo, estado, fecha_creacion)
            VALUES (:reportador_id, :tipo, :servicio_id, :usuario_reportado_id, :motivo, 'pendiente', NOW())";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':reportador_id', $reportador_id);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':servicio_id', $servicio_id);
    $stmt->bindParam(':usuario_reportado_id', $usuario_reportado_id);
    $stmt->bindParam(':motivo', $motivo);
    return $stmt->execute();
}
?>