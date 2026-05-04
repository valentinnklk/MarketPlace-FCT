<?php
// controladores/responder_reserva.php
//
// Endpoint POST que el prestador usa para responder a una solicitud de reserva.
//
// Recibe:
//   - contrato_id      (int)  obligatorio
//   - accion           ('aceptar' | 'rechazar')
//   - notificacion_id  (int)  opcional. Si llega, se marca esa notificación como leída.
//
// Acciones:
//   - rechazar → estado='cancelado' + notificación al cliente
//   - aceptar  → estado='aceptado' + crear conversación + primer mensaje + notificación al cliente
//
// Tras procesar, redirige al perfil (pestaña Notificaciones) o, en caso de aceptar, al chat.

session_start();
require_once __DIR__ . '/../conexion.php';

// ─────────────────────────────────────────────
// Validaciones
// ─────────────────────────────────────────────
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../vista/loginVista.php?server_msg='
        . urlencode('Debes iniciar sesión.') . '&creacion=error');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vista/perfil.php?tab=notificaciones');
    exit;
}

$usuario_id      = (int) $_SESSION['usuario_id'];
$contrato_id     = (int) ($_POST['contrato_id']     ?? 0);
$notificacion_id = (int) ($_POST['notificacion_id'] ?? 0);
$accion          = $_POST['accion'] ?? '';

if ($contrato_id <= 0 || !in_array($accion, ['aceptar', 'rechazar'], true)) {
    header('Location: ../vista/perfil.php?tab=notificaciones&msg='
        . urlencode('Datos incompletos.') . '&estado=error');
    exit;
}

// ─────────────────────────────────────────────
// Cargar contrato + servicio + datos del cliente
// ─────────────────────────────────────────────
$stmt = $conexion->prepare(
    "SELECT c.id, c.cliente_id, c.servicio_id, c.estado, c.fecha_servicio,
            s.prestador_id, s.titulo
     FROM contratos c
     JOIN servicios s ON s.id = c.servicio_id
     WHERE c.id = ?
     LIMIT 1"
);
$stmt->execute([$contrato_id]);
$contrato = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contrato) {
    header('Location: ../vista/perfil.php?tab=notificaciones&msg='
        . urlencode('La reserva no existe.') . '&estado=error');
    exit;
}

if ((int) $contrato['prestador_id'] !== $usuario_id) {
    header('Location: ../vista/perfil.php?tab=notificaciones&msg='
        . urlencode('No estás autorizado a responder esta reserva.') . '&estado=error');
    exit;
}

if ($contrato['estado'] !== 'pendiente') {
    header('Location: ../vista/perfil.php?tab=notificaciones&msg='
        . urlencode('Esta reserva ya ha sido respondida.') . '&estado=error');
    exit;
}

// Helper para marcar como leída la notificación que originó el clic
function marcarNotificacionLeida(PDO $conexion, int $notificacion_id, int $destinatario_id): void {
    if ($notificacion_id <= 0) return;
    $stmt = $conexion->prepare(
        "UPDATE notificaciones_usuario
         SET leida = 1, fecha_lectura = NOW()
         WHERE id = ? AND usuario_destino_id = ?"
    );
    $stmt->execute([$notificacion_id, $destinatario_id]);
}

// =============================================
// RECHAZAR
// =============================================
if ($accion === 'rechazar') {
    try {
        $conexion->beginTransaction();

        // a) Cancelar el contrato
        $stmt = $conexion->prepare(
            "UPDATE contratos
             SET estado = 'cancelado', fecha_actualizacion = NOW()
             WHERE id = ? AND estado = 'pendiente'"
        );
        $stmt->execute([$contrato_id]);

        // b) Notificar al cliente
        $stmt = $conexion->prepare(
            "INSERT INTO notificaciones_usuario
                (usuario_destino_id, titulo, mensaje, tipo, leida, fecha_envio)
             VALUES (?, 'Reserva rechazada', ?, 'reserva_rechazada', 0, NOW())"
        );
        $stmt->execute([
            (int) $contrato['cliente_id'],
            'El prestador ha rechazado tu solicitud para "' . $contrato['titulo'] . '".',
        ]);

        // c) Marcar notificación de origen como leída
        marcarNotificacionLeida($conexion, $notificacion_id, $usuario_id);

        $conexion->commit();
    } catch (PDOException $e) {
        $conexion->rollBack();
        header('Location: ../vista/perfil.php?tab=notificaciones&msg='
            . urlencode('No se pudo rechazar la reserva.') . '&estado=error');
        exit;
    }

    header('Location: ../vista/perfil.php?tab=notificaciones&msg='
        . urlencode('Reserva rechazada.') . '&estado=ok');
    exit;
}

// =============================================
// ACEPTAR
// =============================================
try {
    $conexion->beginTransaction();

    // a) Aceptar el contrato
    $stmt = $conexion->prepare(
        "UPDATE contratos
         SET estado = 'aceptado', fecha_actualizacion = NOW()
         WHERE id = ? AND estado = 'pendiente'"
    );
    $stmt->execute([$contrato_id]);

    if ($stmt->rowCount() === 0) {
        throw new RuntimeException('El contrato ya no está pendiente.');
    }

    // b) Crear conversación entre cliente y prestador, vinculada al contrato
    $stmt = $conexion->prepare(
        "INSERT INTO conversaciones
            (cliente_id, prestador_id, servicio_id, contrato_id,
             fecha_inicio, total_mensajes, no_leidos_cliente, no_leidos_prestador)
         VALUES (?, ?, ?, ?, NOW(), 1, 1, 0)"
    );
    $stmt->execute([
        (int) $contrato['cliente_id'],
        (int) $contrato['prestador_id'],
        (int) $contrato['servicio_id'],
        $contrato_id,
    ]);
    $conversacion_id = (int) $conexion->lastInsertId();

    // c) Primer mensaje del prestador
    $contenido_msg = sprintf(
        'Reserva confirmada para el %s. Coordina los detalles aquí.',
        date('d/m/Y H:i', strtotime($contrato['fecha_servicio']))
    );

    $stmt = $conexion->prepare(
        "INSERT INTO mensajes
            (conversacion_id, remitente_id, contenido, leido, fecha_envio)
         VALUES (?, ?, ?, 0, NOW())"
    );
    $stmt->execute([
        $conversacion_id,
        (int) $contrato['prestador_id'],
        $contenido_msg,
    ]);

    // d) Resumen de la conversación
    $stmt = $conexion->prepare(
        "UPDATE conversaciones
         SET ultimo_mensaje = ?, fecha_ultimo_mensaje = NOW()
         WHERE id = ?"
    );
    $stmt->execute([mb_substr($contenido_msg, 0, 120), $conversacion_id]);

    // e) Notificación al cliente con marcador #conversacion:
    $mensaje_notif = sprintf(
        'Tu reserva para "%s" ha sido aceptada. #conversacion:%d',
        $contrato['titulo'],
        $conversacion_id
    );

    $stmt = $conexion->prepare(
        "INSERT INTO notificaciones_usuario
            (usuario_destino_id, titulo, mensaje, tipo, leida, fecha_envio)
         VALUES (?, 'Reserva aceptada', ?, 'reserva_aceptada', 0, NOW())"
    );
    $stmt->execute([(int) $contrato['cliente_id'], $mensaje_notif]);

    // f) Marcar notificación de origen como leída
    marcarNotificacionLeida($conexion, $notificacion_id, $usuario_id);

    $conexion->commit();

} catch (Exception $e) {
    $conexion->rollBack();
    header('Location: ../vista/perfil.php?tab=notificaciones&msg='
        . urlencode('No se pudo aceptar la reserva: ' . $e->getMessage()) . '&estado=error');
    exit;
}

// Redirigir al chat de esa conversación
header('Location: ../vista/chat.php?chat_id=' . $conversacion_id);
exit;
