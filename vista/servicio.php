<?php
require_once "../conexion.php";
require_once "../controladores/servicioController.php";

if (session_status() === PHP_SESSION_NONE) session_start();

$servicioController = new ServicioController($conexion);
$servicio = $servicioController->mostrarServicioPorId($_GET['id'] ?? 0);

$usuario_logueado = $_SESSION['usuario_id'] ?? null;
$es_propietario   = $servicio && $usuario_logueado && $usuario_logueado == $servicio['prestador_id'];

// Comprobar si el servicio ya está en favoritos del usuario
$es_favorito = false;
if ($usuario_logueado && $servicio) {
    $stmtFav = $conexion->prepare(
        "SELECT 1 FROM favoritos WHERE usuario_id = ? AND servicio_id = ? LIMIT 1"
    );
    $stmtFav->execute([(int)$usuario_logueado, (int)$servicio['id']]);
    $es_favorito = (bool) $stmtFav->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Detalle del Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/estilo.css">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .servicio-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }
        .servicio-card:hover { transform: translateY(-5px); }
        .servicio-imagen { object-fit: cover; height: 300px; width: 100%; }
        .precio { font-size: 1.8rem; font-weight: bold; color: #28a745; }
        .badge-estado { font-size: 0.9rem; }
        .btn-accion { flex: 1; }

        /* Pasos del modal de reserva */
        .paso-reserva { display: none; }
        .paso-reserva.activo { display: block; }
        .paso-indicador {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .paso-indicador .punto {
            width: 30px; height: 30px; border-radius: 50%;
            background: #e9ecef; color: #6c757d;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 0.9rem;
        }
        .paso-indicador .punto.activo { background: #0d6efd; color: #fff; }
        .paso-indicador .punto.completado { background: #28a745; color: #fff; }

        .resumen-fila {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .resumen-fila:last-child { border-bottom: none; }
        .resumen-fila .clave { font-weight: 600; color: #495057; }
    </style>
</head>
<body class="bg-light">
<a class="skip-link" href="#contenido">Saltar al contenido principal</a>

<div class="container py-5" style="max-width: 900px;">

    <?php if ($servicio): ?>

        <!-- Botón Volver fuera del card, encima -->
        <div class="mb-3">
            <a href="home.php" class="btn btn-outline-secondary btn-sm">← Volver</a>
        </div>
        
    <?php if (isset($_GET['reporte'])): ?>
        <div class="alert <?php echo $_GET['reporte'] === 'ok' ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show m-3" role="alert">
            <?php echo $_GET['reporte'] === 'ok' 
                ? '✅ Reporte enviado correctamente.' 
                : '❌ El motivo no puede estar vacío.'; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <script>
            setTimeout(() => document.querySelector('.alert')?.remove(), 3000);
        </script>
    <?php endif; ?>

        <!-- Mensaje flash de favorito -->
        <?php if (isset($_GET['favorito'])): ?>
            <?php if ($_GET['favorito'] === 'agregar'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-heart-fill" aria-hidden="true"></i> Servicio añadido a tus favoritos.
                    <a href="../vista/perfil.php?tab=favoritos" class="alert-link ms-1">Ver favoritos →</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['favorito'] === 'quitar'): ?>
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <i class="bi bi-heart" aria-hidden="true"></i> Servicio eliminado de tus favoritos.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Mensaje flash de reserva -->
        <?php if (isset($_GET['reserva'])): ?>
            <?php if ($_GET['reserva'] === 'ok'): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i> Solicitud de reserva enviada correctamente. El prestador recibirá una notificación.
                </div>
            <?php elseif ($_GET['reserva'] === 'error'): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle-fill" aria-hidden="true"></i> <?php echo htmlspecialchars(urldecode($_GET['msg'] ?? 'No se pudo procesar la reserva.')); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card servicio-card">
            <img src="<?php echo htmlspecialchars($servicio['imagen'] ?? 'https://via.placeholder.com/800x300.png?text=Servicio'); ?>"
                 alt="<?php echo htmlspecialchars($servicio['titulo']); ?>" class="servicio-imagen">

            <div class="card-body p-4">
                <h2 class="card-title mb-3"><?php echo htmlspecialchars($servicio['titulo']); ?></h2>
                <p class="card-text text-muted mb-3"><?php echo nl2br(htmlspecialchars($servicio['descripcion'])); ?></p>
                <p class="precio mb-3">
                    <?php echo number_format($servicio['precio'], 2); ?> €
                    <small class="text-muted fw-normal" style="font-size: 1rem;">
                        / <?php echo htmlspecialchars($servicio['unidad_cobro']); ?>
                    </small>
                </p>
                <span class="badge bg-secondary badge-estado mb-3">
                    <?php echo $servicio['activo'] ? 'Activo' : 'Inactivo'; ?>
                </span>
                <p class="text-muted small mb-4">
                    Prestador:
                    <a href="verUsuarios.php?id=<?php echo (int) $servicio['prestador_id']; ?>">
                        <?php echo htmlspecialchars($servicio['prestador']); ?>
                    </a>
                </p>

                <div class="d-flex gap-2 flex-wrap">
                    <?php if (!$usuario_logueado): ?>
                        <a href="loginVista.php" class="btn btn-primary btn-accion">
                            <i class="bi bi-lock-fill" aria-hidden="true"></i> Inicia sesión para reservar
                        </a>
                        <a href="loginVista.php" class="btn btn-outline-secondary btn-accion">
                            <i class="bi bi-heart" aria-hidden="true"></i> Guardar en favoritos
                        </a>
                    <?php elseif (!$es_propietario && $servicio['activo']): ?>
                        <button type="button" class="btn btn-primary btn-accion"
                                data-bs-toggle="modal" data-bs-target="#modalReserva">
                            <i class="bi bi-calendar-event" aria-hidden="true"></i> Reservar servicio
                        </button>

                        <!-- Botón Favoritos -->
                        <form method="POST" action="../controladores/favoritosController.php" class="btn-accion">
                            <input type="hidden" name="servicio_id" value="<?php echo (int) $servicio['id']; ?>">
                            <input type="hidden" name="accion" value="<?php echo $es_favorito ? 'quitar' : 'agregar'; ?>">
                            <button type="submit"
                                    class="btn w-100 <?php echo $es_favorito ? 'btn-danger' : 'btn-outline-danger'; ?>"
                                    title="<?php echo $es_favorito ? 'Quitar de favoritos' : 'Guardar en favoritos'; ?>">
                                <?php echo $es_favorito ? '<i class="bi bi-heart-fill" aria-hidden="true"></i> En favoritos' : '<i class="bi bi-heart" aria-hidden="true"></i> Guardar'; ?>
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger btn-accion"
                                data-bs-toggle="modal" data-bs-target="#modalReporte">
                            <i class="bi bi-flag-fill" aria-hidden="true"></i> Reportar servicio
                        </button>
                    <?php elseif ($es_propietario): ?>
                        <div class="alert alert-info mb-0 w-100">
                            ℹ️ Este es uno de tus servicios publicados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            Servicio no encontrado.
        </div>
    <?php endif; ?>
</div>

<!-- ============================================================ -->
<!-- MODAL DE RESERVA (3 PASOS)                                   -->
<!-- ============================================================ -->
<?php if ($servicio && $usuario_logueado && !$es_propietario && $servicio['activo']): ?>
<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="dialog" aria-modal="true" tabindex="-1">
        <div class="modal-content" role="dialog" aria-modal="true" tabindex="-1">
            <div class="modal-header" role="dialog" aria-modal="true" tabindex="-1">
                <h5 class="modal-title"><i class="bi bi-calendar-event" aria-hidden="true"></i> Reservar servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formReserva" method="POST" action="../controladores/reservar_servicio.php">
                <input type="hidden" name="servicio_id" value="<?php echo (int) $servicio['id']; ?>">
                <input type="hidden" name="fecha_servicio" id="reservaFechaServicio" value="">

                <div class="modal-body" role="dialog" aria-modal="true" tabindex="-1">
                    <!-- Indicador de pasos -->
                    <div class="paso-indicador">
                        <div class="punto activo" id="puntoPaso1">1</div>
                        <div class="punto"        id="puntoPaso2">2</div>
                        <div class="punto"        id="puntoPaso3">3</div>
                    </div>

                    <!-- Paso 1: Fecha -->
                    <div class="paso-reserva activo" id="paso1">
                        <h6 class="mb-3">1️⃣ Selecciona una fecha</h6>
                        <p class="text-muted small">
                            Solo se pueden reservar fechas a partir de mañana.
                        </p>
                        <input type="text" id="reservaFecha" class="form-control"
                               placeholder="Selecciona una fecha..." required readonly>
                    </div>

                    <!-- Paso 2: Hora -->
                    <div class="paso-reserva" id="paso2">
                        <h6 class="mb-3">2️⃣ Selecciona una hora</h6>
                        <p class="text-muted small">
                            Horario disponible: de 08:00 a 20:00.
                        </p>
                        <select id="reservaHora" name="hora_servicio" class="form-select" required>
                            <option value="">-- Selecciona una hora --</option>
                            <?php for ($h = 8; $h <= 20; $h++): ?>
                                <?php $hh = sprintf('%02d:00', $h); ?>
                                <option value="<?php echo $hh; ?>"><?php echo $hh; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Paso 3: Resumen -->
                    <div class="paso-reserva" id="paso3">
                        <h6 class="mb-3">3️⃣ Confirma tu reserva</h6>
                        <div class="border rounded p-3 bg-light">
                            <div class="resumen-fila">
                                <span class="clave">Servicio</span>
                                <span><?php echo htmlspecialchars($servicio['titulo']); ?></span>
                            </div>
                            <div class="resumen-fila">
                                <span class="clave">Precio</span>
                                <span>
                                    <?php echo number_format($servicio['precio'], 2); ?> €
                                    / <?php echo htmlspecialchars($servicio['unidad_cobro']); ?>
                                </span>
                            </div>
                            <div class="resumen-fila">
                                <span class="clave">Fecha</span>
                                <span id="resumenFecha">—</span>
                            </div>
                            <div class="resumen-fila">
                                <span class="clave">Hora</span>
                                <span id="resumenHora">—</span>
                            </div>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            La solicitud quedará pendiente hasta que el prestador la acepte.
                        </p>
                    </div>
                </div>

                <div class="modal-footer" role="dialog" aria-modal="true" tabindex="-1">
                    <button type="button" class="btn btn-outline-secondary" id="btnReservaAtras" disabled>
                        ← Atrás
                    </button>
                    <button type="button" class="btn btn-primary" id="btnReservaSiguiente" disabled>
                        Siguiente →
                    </button>
                    <button type="submit" class="btn btn-success d-none" id="btnReservaConfirmar">
                        <i class="bi bi-check-circle-fill" aria-hidden="true"></i> Confirmar reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================ -->
<!-- MODAL DE REPORTE                                             -->
<!-- ============================================================ -->
<?php if ($servicio && $usuario_logueado && !$es_propietario): ?>
<div class="modal fade" id="modalReporte" tabindex="-1" role="dialog" aria-modal="true" tabindex="-1">
    <div class="modal-dialog" role="dialog" aria-modal="true" tabindex="-1">
        <div class="modal-content" role="dialog" aria-modal="true" tabindex="-1">
            <div class="modal-header" role="dialog" aria-modal="true" tabindex="-1">
                <h5 class="modal-title"><i class="bi bi-flag-fill" aria-hidden="true"></i> Reportar servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../controladores/reportarController.php" method="POST">
                <div class="modal-body" role="dialog" aria-modal="true" tabindex="-1">
                    <input type="hidden" name="tipo" value="servicio">
                    <input type="hidden" name="servicio_id" value="<?php echo (int) $servicio['id']; ?>">
                    <input type="hidden" name="usuario_reportado_id" value="">
                    <input type="hidden" name="redirect" value="../vista/servicio.php?id=<?php echo (int) $servicio['id']; ?>">

                    

                    <label class="form-label">Motivo del reporte</label>
                    <select name="motivo" class="form-select mb-3" required>
                        <option value="">-- Selecciona un motivo --</option>
                        <option value="Precio abusivo">Precio abusivo</option>
                        <option value="Contenido inapropiado">Contenido inapropiado</option>
                        <option value="Servicio fraudulento">Servicio fraudulento</option>
                        <option value="Información falsa">Información falsa</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="modal-footer" role="dialog" aria-modal="true" tabindex="-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Enviar reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<script>
(function () {
    var formReserva = document.getElementById('formReserva');
    if (!formReserva) return;

    var pasoActual = 1;
    var fechaElegida = null;
    var horaElegida  = null;

    var inputFechaVisible = document.getElementById('reservaFecha');
    var selectHora        = document.getElementById('reservaHora');
    var inputHidden       = document.getElementById('reservaFechaServicio');

    var btnAtras     = document.getElementById('btnReservaAtras');
    var btnSiguiente = document.getElementById('btnReservaSiguiente');
    var btnConfirmar = document.getElementById('btnReservaConfirmar');

    var paso1 = document.getElementById('paso1');
    var paso2 = document.getElementById('paso2');
    var paso3 = document.getElementById('paso3');

    var puntos = [
        document.getElementById('puntoPaso1'),
        document.getElementById('puntoPaso2'),
        document.getElementById('puntoPaso3')
    ];

    flatpickr.localize(flatpickr.l10ns.es);
    var fp = flatpickr(inputFechaVisible, {
        dateFormat: "Y-m-d",
        minDate: "tomorrow",
        disableMobile: true,
        onChange: function (selectedDates, dateStr) {
            fechaElegida = dateStr || null;
            actualizarBotones();
        }
    });

    selectHora.addEventListener('change', function () {
        horaElegida = this.value || null;
        actualizarBotones();
    });

    btnSiguiente.addEventListener('click', function () {
        if (pasoActual < 3) { pasoActual++; renderPaso(); }
    });

    btnAtras.addEventListener('click', function () {
        if (pasoActual > 1) { pasoActual--; renderPaso(); }
    });

    formReserva.addEventListener('submit', function (e) {
        if (!fechaElegida || !horaElegida) { e.preventDefault(); return; }
        inputHidden.value = fechaElegida + ' ' + horaElegida + ':00';
    });

    function renderPaso() {
        paso1.classList.toggle('activo', pasoActual === 1);
        paso2.classList.toggle('activo', pasoActual === 2);
        paso3.classList.toggle('activo', pasoActual === 3);

        for (var i = 0; i < puntos.length; i++) {
            puntos[i].classList.remove('activo', 'completado');
            if (i + 1 < pasoActual)      puntos[i].classList.add('completado');
            else if (i + 1 === pasoActual) puntos[i].classList.add('activo');
        }

        if (pasoActual === 3) {
            document.getElementById('resumenFecha').textContent = fechaElegida || '—';
            document.getElementById('resumenHora').textContent  = horaElegida || '—';
        }
        actualizarBotones();
    }

    function actualizarBotones() {
        btnAtras.disabled = (pasoActual === 1);

        if (pasoActual === 3) {
            btnSiguiente.classList.add('d-none');
            btnConfirmar.classList.remove('d-none');
            btnConfirmar.disabled = !(fechaElegida && horaElegida);
        } else {
            btnSiguiente.classList.remove('d-none');
            btnConfirmar.classList.add('d-none');
            if (pasoActual === 1) btnSiguiente.disabled = !fechaElegida;
            else if (pasoActual === 2) btnSiguiente.disabled = !horaElegida;
        }
    }

    var modalEl = document.getElementById('modalReserva');
    modalEl.addEventListener('hidden.bs.modal', function () {
        pasoActual = 1;
        fechaElegida = null;
        horaElegida  = null;
        fp.clear();
        selectHora.value = '';
        inputHidden.value = '';
        renderPaso();
    });

    renderPaso();
})();

</script>


<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
</body>
</html>