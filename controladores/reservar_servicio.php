<?php
// controladores/reservar_servicio.php
//
// Endpoint POST que recibe:
//   - servicio_id     (int)
//   - modo_reserva    (string: hora|dia|sesion|trabajo|proyecto)
//   - fecha_servicio  (string 'YYYY-MM-DD HH:MM:SS') — inicio del servicio
//   - fecha_fin       (string 'YYYY-MM-DD HH:MM:SS') — fin del servicio
//
// 1) Valida que el modo coincide con la unidad de cobro del servicio.
// 2) Valida que fecha_fin sea posterior a fecha_servicio.
// 3) Crea un contrato en estado 'pendiente'.
// 4) Notifica al prestador con tipo='nueva_reserva'.
// 5) Redirige al detalle del servicio con un mensaje flash.

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
$modo_reserva   = strtolower(trim($_POST['modo_reserva']  ?? ''));
$fecha_servicio = trim($_POST['fecha_servicio'] ?? '');
$fecha_fin      = trim($_POST['fecha_fin']      ?? '');

// ─────────────────────────────────────────────
// Validaciones de datos
// ─────────────────────────────────────────────
function redirigirError(int $servicio_id, string $msg): void {
    $url = '../vista/servicio.php?id=' . $servicio_id
         . '&reserva=error&msg=' . urlencode($msg);
    header('Location: ' . $url);
    exit;
}

if ($servicio_id <= 0 || $fecha_servicio === '' || $fecha_fin === '') {
    redirigirError($servicio_id, 'Datos incompletos.');
}

$modos_validos = ['hora', 'dia', 'sesion', 'trabajo', 'proyecto'];
if (!in_array($modo_reserva, $modos_validos, true)) {
    redirigirError($servicio_id, 'Modo de reserva no válido.');
}

// Validar formato de ambas fechas
$dt_ini = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_servicio);
$dt_fin = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_fin);
if (!$dt_ini || $dt_ini->format('Y-m-d H:i:s') !== $fecha_servicio) {
    redirigirError($servicio_id, 'Formato de fecha de inicio inválido.');
}
if (!$dt_fin || $dt_fin->format('Y-m-d H:i:s') !== $fecha_fin) {
    redirigirError($servicio_id, 'Formato de fecha de fin inválido.');
}

// Las fechas deben ser estrictamente futuras
if ($dt_ini <= new DateTime('now')) {
    redirigirError($servicio_id, 'La fecha de inicio debe ser posterior al momento actual.');
}
if ($dt_fin <= $dt_ini) {
    redirigirError($servicio_id, 'La fecha de fin debe ser posterior a la de inicio.');
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

// Comprobar que el modo enviado coincide con la unidad de cobro del servicio
if (strtolower($servicio['unidad_cobro']) !== $modo_reserva) {
    redirigirError($servicio_id, 'El tipo de reserva no coincide con el del servicio.');
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

    // a) INSERT contrato (con fecha_fin nueva)
    $stmt = $conexion->prepare(
        "INSERT INTO contratos
            (cliente_id, servicio_id, precio_acordado, fecha_contrato,
             fecha_servicio, fecha_fin,
             estado, confirmacion_cliente, confirmacion_prestador)
         VALUES (?, ?, ?, NOW(), ?, ?, 'pendiente', 0, 0)"
    );
    $stmt->execute([
        $cliente_id,
        $servicio_id,
        $servicio['precio'],
        $fecha_servicio,
        $fecha_fin,
    ]);
    $contrato_id = (int) $conexion->lastInsertId();

    // b) INSERT notificación al prestador (texto adaptado al modo)
    $titulo = 'Nueva solicitud de reserva';

    if ($modo_reserva === 'dia') {
        $textoFecha = 'el ' . $dt_ini->format('d/m/Y') . ' (día completo)';
    } elseif ($modo_reserva === 'proyecto') {
        $textoFecha = 'del ' . $dt_ini->format('d/m/Y')
                    . ' al ' . $dt_fin->format('d/m/Y');
    } else {
        // hora / sesion / trabajo
        $textoFecha = 'el ' . $dt_ini->format('d/m/Y')
                    . ' de ' . $dt_ini->format('H:i')
                    . ' a ' . $dt_fin->format('H:i');
    }

    $mensaje = sprintf(
        '%s quiere reservar "%s" %s. #contrato:%d',
        $nombre_cliente,
        $servicio['titulo'],
        $textoFecha,
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
