<?php
// controladores/reservar_servicio.php
//
// Endpoint POST que recibe:
//   - servicio_id     (int)
//   - fecha_servicio  (string 'YYYY-MM-DD HH:MM:SS')
//
// 1) Crea un contrato en estado 'pendiente'.
// 2) Notifica al prestador con tipo='nueva_reserva'.
// 3) Redirige al detalle del servicio con un mensaje flash.

session_start();
require_once __DIR__ . '/../conexion.php';

// ─────────────────────────────────────────────
// Validaciones de acceso
// ─────────────────────────────────────────────
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../vista/loginVista.php?server_msg='
        . urlencode('Debes iniciar sesión para reservar un servicio.')
        . '&creacion=error');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../vista/home.php');
    exit;
}

$cliente_id     = (int) $_SESSION['usuario_id'];
$servicio_id    = (int) ($_POST['servicio_id']    ?? 0);
$fecha_servicio = trim($_POST['fecha_servicio']  ?? '');

// ─────────────────────────────────────────────
// Validaciones de datos
// ─────────────────────────────────────────────
function redirigirError(int $servicio_id, string $msg): void {
    $url = '../vista/servicio.php?id=' . $servicio_id
         . '&reserva=error&msg=' . urlencode($msg);
    header('Location: ' . $url);
    exit;
}

if ($servicio_id <= 0 || $fecha_servicio === '') {
    redirigirError($servicio_id, 'Datos incompletos.');
}

// Validar formato de fecha 'YYYY-MM-DD HH:MM:SS'
$dt = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_servicio);
if (!$dt || $dt->format('Y-m-d H:i:s') !== $fecha_servicio) {
    redirigirError($servicio_id, 'Formato de fecha inválido.');
}

// La fecha debe ser estrictamente futura
if ($dt <= new DateTime('now')) {
    redirigirError($servicio_id, 'La fecha de la reserva debe ser posterior al momento actual.');
}

// ─────────────────────────────────────────────
// Cargar servicio + cliente (para mensaje)
// ─────────────────────────────────────────────
$stmt = $conexion->prepare(
    "SELECT s.id, s.prestador_id, s.titulo, s.precio, s.unidad_cobro, s.activo
     FROM servicios s
     WHERE s.id = ?
     LIMIT 1"
);
$stmt->execute([$servicio_id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$servicio) {
    redirigirError($servicio_id, 'El servicio no existe.');
}
if (!$servicio['activo']) {
    redirigirError($servicio_id, 'El servicio no está activo en este momento.');
}
if ((int) $servicio['prestador_id'] === $cliente_id) {
    redirigirError($servicio_id, 'No puedes reservar tu propio servicio.');
}

$stmt = $conexion->prepare("SELECT nombre FROM usuarios WHERE id = ? LIMIT 1");
$stmt->execute([$cliente_id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);
$nombre_cliente = $cliente['nombre'] ?? 'Un usuario';

// ─────────────────────────────────────────────
// Transacción: contrato + notificación al prestador
// ─────────────────────────────────────────────
try {
    $conexion->beginTransaction();

    // a) INSERT contrato
    $stmt = $conexion->prepare(
        "INSERT INTO contratos
            (cliente_id, servicio_id, precio_acordado, fecha_contrato, fecha_servicio,
             estado, confirmacion_cliente, confirmacion_prestador)
         VALUES (?, ?, ?, NOW(), ?, 'pendiente', 0, 0)"
    );
    $stmt->execute([
        $cliente_id,
        $servicio_id,
        $servicio['precio'],
        $fecha_servicio,
    ]);
    $contrato_id = (int) $conexion->lastInsertId();

    // b) INSERT notificación al prestador
    //    El marcador "#contrato:N" al final permite recuperar el id desde la vista.
    $titulo  = 'Nueva solicitud de reserva';
    $mensaje = sprintf(
        '%s quiere reservar "%s" para el %s. #contrato:%d',
        $nombre_cliente,
        $servicio['titulo'],
        date('d/m/Y H:i', strtotime($fecha_servicio)),
        $contrato_id
    );

    $stmt = $conexion->prepare(
        "INSERT INTO notificaciones_usuario
            (usuario_destino_id, titulo, mensaje, tipo, leida, fecha_envio)
         VALUES (?, ?, ?, 'nueva_reserva', 0, NOW())"
    );
    $stmt->execute([
        (int) $servicio['prestador_id'],
        $titulo,
        $mensaje,
    ]);

    $conexion->commit();

} catch (PDOException $e) {
    $conexion->rollBack();
    redirigirError($servicio_id, 'No se pudo registrar la reserva. Inténtalo de nuevo.');
}

// ─────────────────────────────────────────────
// Éxito → volvemos al detalle del servicio
// ─────────────────────────────────────────────
header('Location: ../vista/servicio.php?id=' . $servicio_id . '&reserva=ok');
exit;
