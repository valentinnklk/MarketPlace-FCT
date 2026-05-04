<?php
// controladores/finalizar_servicio.php
//
// Endpoint POST para la doble confirmación de finalización de un contrato.
// Recibe:
//   - contrato_id      (int) obligatorio
//   - conversacion_id  (int) obligatorio (para volver al chat)
//
// Lógica:
//   - Si el usuario es cliente   → confirmacion_cliente = 1
//   - Si el usuario es prestador → confirmacion_prestador = 1
//   - Tras la actualización, si AMBAS confirmaciones están a 1:
//       a) estado = 'completado', fecha_actualizacion = NOW()
//       b) Notificación al cliente con tipo='servicio_finalizado'
//          y marcador #contrato:[id] para enlazar a reseñaVista.php

session_start();
require_once __DIR__ . '/../conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../vista/loginVista.php?server_msg='
        . urlencode('Debes iniciar sesión.') . '&creacion=error');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vista/home.php');
    exit;
}

$usuario_id      = (int) $_SESSION['usuario_id'];
$contrato_id     = (int) ($_POST['contrato_id']     ?? 0);
$conversacion_id = (int) ($_POST['conversacion_id'] ?? 0);

if ($contrato_id <= 0 || $conversacion_id <= 0) {
    header('Location: ../vista/chat.php');
    exit;
}

// ─────────────────────────────────────────────
// Cargar contrato + servicio (para conocer roles)
// ─────────────────────────────────────────────
$stmt = $conexion->prepare(
    "SELECT c.id, c.cliente_id, c.servicio_id, c.estado, c.fecha_servicio,
            c.confirmacion_cliente, c.confirmacion_prestador,
            s.prestador_id, s.titulo
     FROM contratos c
     JOIN servicios s ON s.id = c.servicio_id
     WHERE c.id = ?
     LIMIT 1"
);
$stmt->execute([$contrato_id]);
$contrato = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contrato) {
    header('Location: ../vista/chat.php?chat_id=' . $conversacion_id);
    exit;
}

// El usuario debe ser cliente o prestador del contrato
$es_cliente   = ((int) $contrato['cliente_id']   === $usuario_id);
$es_prestador = ((int) $contrato['prestador_id'] === $usuario_id);
if (!$es_cliente && !$es_prestador) {
    header('Location: ../vista/chat.php?chat_id=' . $conversacion_id);
    exit;
}

// El estado debe permitir finalización y la fecha del servicio ya debe haber pasado
if (!in_array($contrato['estado'], ['aceptado', 'en_proceso'], true)) {
    header('Location: ../vista/chat.php?chat_id=' . $conversacion_id);
    exit;
}
if (strtotime($contrato['fecha_servicio']) > time()) {
    header('Location: ../vista/chat.php?chat_id=' . $conversacion_id);
    exit;
}

// ─────────────────────────────────────────────
// Actualizar la columna correspondiente
// ─────────────────────────────────────────────
try {
    $conexion->beginTransaction();

    if ($es_cliente) {
        $stmt = $conexion->prepare(
            "UPDATE contratos
             SET confirmacion_cliente = 1, fecha_actualizacion = NOW()
             WHERE id = ?"
        );
    } else {
        $stmt = $conexion->prepare(
            "UPDATE contratos
             SET confirmacion_prestador = 1, fecha_actualizacion = NOW()
             WHERE id = ?"
        );
    }
    $stmt->execute([$contrato_id]);

    // Recargar para comprobar la doble confirmación
    $stmt = $conexion->prepare(
        "SELECT confirmacion_cliente, confirmacion_prestador, estado
         FROM contratos WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$contrato_id]);
    $estado_actual = $stmt->fetch(PDO::FETCH_ASSOC);

    $ambas_a_uno = (
           (int) $estado_actual['confirmacion_cliente']   === 1
        && (int) $estado_actual['confirmacion_prestador'] === 1
    );

    if ($ambas_a_uno && $estado_actual['estado'] !== 'completado') {

        // a) Marcar el contrato como completado
        $stmt = $conexion->prepare(
            "UPDATE contratos
             SET estado = 'completado', fecha_actualizacion = NOW()
             WHERE id = ?"
        );
        $stmt->execute([$contrato_id]);

        // b) Notificación al cliente para que valore
        $mensaje = sprintf(
            'Tu servicio "%s" se ha completado. Valora al prestador. #contrato:%d',
            $contrato['titulo'],
            $contrato_id
        );
        $stmt = $conexion->prepare(
            "INSERT INTO notificaciones_usuario
                (usuario_destino_id, titulo, mensaje, tipo, leida, fecha_envio)
             VALUES (?, 'Servicio completado', ?, 'servicio_finalizado', 0, NOW())"
        );
        $stmt->execute([(int) $contrato['cliente_id'], $mensaje]);
    }

    $conexion->commit();

} catch (PDOException $e) {
    $conexion->rollBack();
}

header('Location: ../vista/chat.php?chat_id=' . $conversacion_id);
exit;
