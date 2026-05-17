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
        /* Botones de acción del servicio: grid uniforme, alturas iguales */
        .acciones-servicio {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: .65rem;
            align-items: stretch;
        }
        .acciones-servicio > form {
            margin: 0;
            display: flex;     /* el form se estira a toda la celda */
        }
        .acciones-servicio > form > .btn,
        .acciones-servicio > .btn {
            width: 100%;
            min-height: 46px;
            margin: 0;          /* sin margen heredado de Bootstrap */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            white-space: nowrap;
        }
        .acciones-servicio > form > .btn {
            flex: 1;            /* el botón ocupa toda la altura/ancho del form */
        }

        /* === Sección de valoraciones === */
        .valoraciones-servicio {
            border: 1px solid #E5E7EB;
            border-radius: 14px;
        }
        .valoraciones-cabecera {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding-bottom: 1.25rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid #E5E7EB;
        }
        .valoraciones-cabecera h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #111827;
            display: inline-flex;
            align-items: center;
            gap: .55rem;
        }
        .valoraciones-cabecera h3 .bi { color: #F59E0B; }

        .valoraciones-resumen {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            background: #FFFBEB;
            border: 1px solid #FCD34D;
            border-radius: 999px;
            padding: .4rem 1rem;
        }
        .valoraciones-media {
            font-size: 1.4rem;
            font-weight: 800;
            color: #92400E;
            font-family: 'IBM Plex Mono', monospace;
            line-height: 1;
        }
        .valoraciones-estrellas {
            color: #F59E0B;
            font-size: .9rem;
        }
        .valoraciones-total {
            font-size: .82rem;
            color: #6B7280;
        }

        .valoraciones-vacio {
            text-align: center;
            padding: 2rem 1rem;
            color: #6B7280;
        }
        .valoraciones-vacio .bi {
            font-size: 2.5rem;
            color: #D1D5DB;
            display: block;
            margin-bottom: .5rem;
        }
        .valoraciones-vacio p {
            margin: 0;
            font-size: .92rem;
        }

        .valoraciones-lista {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .valoracion-item {
            display: flex;
            gap: 1rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #F2F4F7;
        }
        .valoracion-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .valoracion-avatar {
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: #fff;
            font-weight: 700;
            font-size: .85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .valoracion-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .valoracion-contenido {
            flex: 1;
            min-width: 0;
        }
        .valoracion-meta {
            display: flex;
            align-items: center;
            gap: .65rem;
            flex-wrap: wrap;
            margin-bottom: .35rem;
        }
        .valoracion-autor {
            color: #111827;
            font-size: .95rem;
            font-weight: 600;
        }
        .valoracion-estrellas {
            color: #F59E0B;
            font-size: .85rem;
            letter-spacing: 1px;
        }
        .valoracion-fecha {
            color: #9CA3AF;
            font-size: .78rem;
            margin-left: auto;
        }
        .valoracion-texto {
            margin: 0;
            color: #374151;
            font-size: .92rem;
            line-height: 1.55;
        }
        .valoracion-sin-texto {
            color: #9CA3AF;
        }

        /* === Modal de reserva — formulario único === */
        .reserva-resumen {
            background: linear-gradient(135deg, #F8FBFF 0%, #EEF4FF 100%);
            border: 1px solid rgba(37, 99, 235, 0.18);
            border-radius: 12px;
            padding: .85rem 1.1rem;
        }
        .reserva-resumen-titulo {
            font-weight: 700;
            color: #111827;
            font-size: 1.02rem;
            margin-bottom: .15rem;
        }
        .reserva-resumen-precio {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            color: #1D4ED8;
            font-size: 1.05rem;
        }
        .reserva-bloque {
            margin-bottom: 1.1rem;
            padding-bottom: 1.1rem;
            border-bottom: 1px dashed #E5E7EB;
        }
        .reserva-bloque:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }
        .reserva-bloque .form-label {
            font-weight: 600;
            color: #111827;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
        }
        .reserva-bloque .form-label .bi { color: #2563EB; }
        .reserva-bloque .form-text {
            font-size: .8rem;
            color: #6B7280;
            margin-top: .35rem;
        }
        .reserva-flecha {
            color: #9CA3AF;
            display: flex;
            align-items: center;
            font-size: 1rem;
        }
        .reserva-info {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            background: #E8F1FF;
            color: #1D4ED8;
            padding: .65rem .9rem;
            border-radius: 8px;
            font-size: .85rem;
            margin-top: 1rem;
        }
        .reserva-info .bi { font-size: 1rem; margin-top: 1px; }
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

<?php
    // Cargar imágenes del servicio
    require_once __DIR__ . '/../modelo/imagenServicioModelo.php';
    $imgModelo = new ImagenServicioModelo($conexion);
    $imagenes_servicio = $imgModelo->getImagenesPorServicio((int) $servicio['id']);
    $img_principal = !empty($imagenes_servicio)
        ? htmlspecialchars($imagenes_servicio[0]['url_publica'])
        : 'https://via.placeholder.com/800x300.png?text=Sin+imagen';

    // Cargar valoraciones del servicio (las que dejaron los clientes que ya lo contrataron)
    require_once __DIR__ . '/../modelo/resenaModelo.php';
    $resenaModelo = new ReseñaModelo($conexion);
    $valoraciones_servicio = $resenaModelo->getReseñasPorServicio((int) $servicio['id'], 20);
    $stats_servicio = $resenaModelo->getEstadisticasServicio((int) $servicio['id']);
?>
        <div class="card servicio-card">
            <img id="servicioImagenPrincipal"
                 src="<?php echo $img_principal; ?>"
                 alt="<?php echo htmlspecialchars($servicio['titulo']); ?>" class="servicio-imagen">

            <?php if (count($imagenes_servicio) > 1): ?>
                <div class="servicio-galeria-miniaturas">
                    <?php foreach ($imagenes_servicio as $idx => $img): ?>
                        <button type="button"
                                class="servicio-miniatura<?php echo $idx === 0 ? ' activa' : ''; ?>"
                                data-url="<?php echo htmlspecialchars($img['url_publica']); ?>"
                                aria-label="Ver imagen <?php echo $idx + 1; ?>">
                            <img src="<?php echo htmlspecialchars($img['url_publica']); ?>"
                                 alt="Miniatura <?php echo $idx + 1; ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

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

                <div class="acciones-servicio">
                    <?php if (!$usuario_logueado): ?>
                        <a href="loginVista.php" class="btn btn-primary">
                            <i class="bi bi-lock-fill" aria-hidden="true"></i> Inicia sesión para reservar
                        </a>
                        <a href="loginVista.php" class="btn btn-outline-secondary">
                            <i class="bi bi-heart" aria-hidden="true"></i> Guardar en favoritos
                        </a>
                    <?php elseif (!$es_propietario && $servicio['activo']): ?>
                        <button type="button" class="btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#modalReserva">
                            <i class="bi bi-calendar-event" aria-hidden="true"></i> Reservar servicio
                        </button>

                        <!-- Botón Enviar mensaje al prestador (sin reservar) -->
                        <form method="POST" action="chat.php?accion=abrir">
                            <input type="hidden" name="prestador_id" value="<?php echo (int) $servicio['prestador_id']; ?>">
                            <input type="hidden" name="servicio_id"  value="<?php echo (int) $servicio['id']; ?>">
                            <button type="submit" class="btn btn-outline-primary"
                                    title="Pregunta cualquier duda al prestador sin reservar todavía">
                                <i class="bi bi-chat-dots-fill" aria-hidden="true"></i> Enviar mensaje
                            </button>
                        </form>

                        <!-- Botón Favoritos -->
                        <form method="POST" action="../controladores/favoritosController.php">
                            <input type="hidden" name="servicio_id" value="<?php echo (int) $servicio['id']; ?>">
                            <input type="hidden" name="accion" value="<?php echo $es_favorito ? 'quitar' : 'agregar'; ?>">
                            <button type="submit"
                                    class="btn <?php echo $es_favorito ? 'btn-danger' : 'btn-outline-danger'; ?>"
                                    title="<?php echo $es_favorito ? 'Quitar de favoritos' : 'Guardar en favoritos'; ?>">
                                <?php echo $es_favorito ? '<i class="bi bi-heart-fill" aria-hidden="true"></i> En favoritos' : '<i class="bi bi-heart" aria-hidden="true"></i> Guardar'; ?>
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#modalReporte">
                            <i class="bi bi-flag-fill" aria-hidden="true"></i> Reportar servicio
                        </button>
                    <?php elseif ($es_propietario): ?>
                        <div class="alert alert-info mb-0" style="grid-column: 1 / -1;">
                            <i class="bi bi-info-circle-fill" aria-hidden="true"></i> Este es uno de tus servicios publicados.
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

    <!-- ============================================================ -->
    <!-- VALORACIONES DEL SERVICIO                                    -->
    <!-- ============================================================ -->
    <?php if ($servicio): ?>
    <section class="card mt-4 valoraciones-servicio" aria-labelledby="tituloValoraciones">
        <div class="card-body">
            <header class="valoraciones-cabecera">
                <h3 id="tituloValoraciones" class="mb-0">
                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                    Valoraciones de clientes
                </h3>
                <?php if ($stats_servicio['total'] > 0): ?>
                    <div class="valoraciones-resumen">
                        <span class="valoraciones-media"><?php echo number_format($stats_servicio['media'], 1); ?></span>
                        <span class="valoraciones-estrellas">
                            <?php
                            $media = (int) round($stats_servicio['media']);
                            echo str_repeat('<i class="bi bi-star-fill" aria-hidden="true"></i>', $media);
                            echo str_repeat('<i class="bi bi-star" aria-hidden="true"></i>', 5 - $media);
                            ?>
                        </span>
                        <span class="valoraciones-total">
                            (<?php echo $stats_servicio['total']; ?>
                            valoraci<?php echo $stats_servicio['total'] === 1 ? 'ón' : 'ones'; ?>)
                        </span>
                    </div>
                <?php endif; ?>
            </header>

            <?php if (empty($valoraciones_servicio)): ?>
                <div class="valoraciones-vacio">
                    <i class="bi bi-chat-square-text" aria-hidden="true"></i>
                    <p>
                        Este servicio aún no tiene valoraciones.
                        <?php if ($usuario_logueado && !$es_propietario): ?>
                            ¡Sé el primero en reservarlo y dejar tu reseña!
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <ul class="valoraciones-lista" role="list">
                    <?php foreach ($valoraciones_servicio as $v): ?>
                        <li class="valoracion-item">
                            <div class="valoracion-avatar" aria-hidden="true">
                                <?php if (!empty($v['autor_avatar'])): ?>
                                    <img src="<?php echo htmlspecialchars($v['autor_avatar']); ?>"
                                         alt="">
                                <?php else: ?>
                                    <?php echo strtoupper(substr($v['autor'], 0, 2)); ?>
                                <?php endif; ?>
                            </div>
                            <div class="valoracion-contenido">
                                <div class="valoracion-meta">
                                    <strong class="valoracion-autor"><?php echo htmlspecialchars($v['autor']); ?></strong>
                                    <span class="valoracion-estrellas">
                                        <?php
                                        $p = (int) $v['puntuacion'];
                                        echo str_repeat('<i class="bi bi-star-fill" aria-hidden="true"></i>', $p);
                                        echo str_repeat('<i class="bi bi-star" aria-hidden="true"></i>', 5 - $p);
                                        ?>
                                    </span>
                                    <span class="valoracion-fecha">
                                        <?php echo date('d/m/Y', strtotime($v['fecha'])); ?>
                                    </span>
                                </div>
                                <?php if (!empty($v['comentario'])): ?>
                                    <p class="valoracion-texto"><?php echo nl2br(htmlspecialchars($v['comentario'])); ?></p>
                                <?php else: ?>
                                    <p class="valoracion-texto valoracion-sin-texto">
                                        <em>El cliente no dejó comentario escrito.</em>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<!-- ============================================================ -->
<!-- MODAL DE RESERVA (formulario único, controles según unidad)  -->
<!-- ============================================================ -->
<?php if ($servicio && $usuario_logueado && !$es_propietario && $servicio['activo']): ?>
<?php
    // Normalizamos la unidad de cobro a uno de los modos soportados.
    // Si llega un valor desconocido (por compatibilidad), usamos "trabajo".
    $modoReserva = strtolower((string) ($servicio['unidad_cobro'] ?? 'trabajo'));
    if (!in_array($modoReserva, ['hora','dia','sesion','trabajo','proyecto'], true)) {
        $modoReserva = 'trabajo';
    }
?>
<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                    Reservar servicio
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formReserva" method="POST" action="../controladores/reservar_servicio.php" novalidate>
                <input type="hidden" name="servicio_id"    value="<?php echo (int) $servicio['id']; ?>">
                <input type="hidden" name="modo_reserva"   value="<?php echo htmlspecialchars($modoReserva); ?>">
                <input type="hidden" name="fecha_servicio" id="reservaFechaServicio" value="">
                <input type="hidden" name="fecha_fin"      id="reservaFechaFin"      value="">

                <div class="modal-body">

                    <!-- Resumen del servicio (siempre visible arriba) -->
                    <div class="reserva-resumen mb-3">
                        <div class="reserva-resumen-titulo"><?php echo htmlspecialchars($servicio['titulo']); ?></div>
                        <div class="reserva-resumen-precio">
                            <?php echo number_format($servicio['precio'], 2); ?> €
                            <span class="text-muted">/ <?php echo htmlspecialchars($servicio['unidad_cobro']); ?></span>
                        </div>
                    </div>

                    <!-- ──────────────────────────────────────────── -->
                    <!-- BLOQUE: SOLO DÍA  (unidad = dia)              -->
                    <!-- ──────────────────────────────────────────── -->
                    <?php if ($modoReserva === 'dia'): ?>
                        <div class="reserva-bloque">
                            <label class="form-label" for="reservaDiaUnico">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                Día del servicio
                            </label>
                            <input type="text" id="reservaDiaUnico" class="form-control"
                                   placeholder="Selecciona un día..." required readonly>
                            <p class="form-text">
                                Este servicio se contrata por día completo. Selecciona el día en que necesitas el servicio.
                            </p>
                        </div>

                    <!-- ──────────────────────────────────────────── -->
                    <!-- BLOQUE: HORA  (unidad = hora)                 -->
                    <!-- ──────────────────────────────────────────── -->
                    <?php elseif ($modoReserva === 'hora'): ?>
                        <div class="reserva-bloque">
                            <label class="form-label" for="reservaDiaHora">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                Día del servicio
                            </label>
                            <input type="text" id="reservaDiaHora" class="form-control"
                                   placeholder="Selecciona un día..." required readonly>
                        </div>

                        <div class="reserva-bloque">
                            <label class="form-label">
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                Franja horaria (en punto, sin minutos)
                            </label>
                            <div class="row g-2">
                                <div class="col">
                                    <select id="reservaHoraInicio" class="form-select" required>
                                        <option value="">Hora inicio...</option>
                                        <?php for ($h = 7; $h <= 22; $h++): ?>
                                            <option value="<?php echo sprintf('%02d:00', $h); ?>">
                                                <?php echo sprintf('%02d:00', $h); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-auto reserva-flecha"><i class="bi bi-arrow-right"></i></div>
                                <div class="col">
                                    <select id="reservaHoraFin" class="form-select" required>
                                        <option value="">Hora fin...</option>
                                        <?php for ($h = 8; $h <= 23; $h++): ?>
                                            <option value="<?php echo sprintf('%02d:00', $h); ?>">
                                                <?php echo sprintf('%02d:00', $h); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <p class="form-text">
                                La hora final debe ser posterior a la inicial. Se cobrará por las horas reservadas.
                            </p>
                        </div>

                    <!-- ──────────────────────────────────────────── -->
                    <!-- BLOQUE: SESIÓN / TRABAJO  (con minutos)       -->
                    <!-- ──────────────────────────────────────────── -->
                    <?php elseif ($modoReserva === 'sesion' || $modoReserva === 'trabajo'): ?>
                        <div class="reserva-bloque">
                            <label class="form-label" for="reservaDiaSesion">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                Día del servicio
                            </label>
                            <input type="text" id="reservaDiaSesion" class="form-control"
                                   placeholder="Selecciona un día..." required readonly>
                        </div>

                        <div class="reserva-bloque">
                            <label class="form-label">
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                Franja horaria (con minutos)
                            </label>
                            <div class="row g-2 align-items-center">
                                <div class="col">
                                    <input type="time" id="reservaHoraInicioMin" class="form-control"
                                           step="300" required>
                                </div>
                                <div class="col-auto reserva-flecha"><i class="bi bi-arrow-right"></i></div>
                                <div class="col">
                                    <input type="time" id="reservaHoraFinMin" class="form-control"
                                           step="300" required>
                                </div>
                            </div>
                            <p class="form-text">
                                Elige la hora de inicio y de fin con la precisión que necesites.
                            </p>
                        </div>

                    <!-- ──────────────────────────────────────────── -->
                    <!-- BLOQUE: PROYECTO  (rango de días)             -->
                    <!-- ──────────────────────────────────────────── -->
                    <?php elseif ($modoReserva === 'proyecto'): ?>
                        <div class="reserva-bloque">
                            <label class="form-label" for="reservaRangoDias">
                                <i class="bi bi-calendar-range" aria-hidden="true"></i>
                                Periodo del proyecto
                            </label>
                            <input type="text" id="reservaRangoDias" class="form-control"
                                   placeholder="Selecciona inicio y fin..." required readonly>
                            <p class="form-text">
                                Selecciona el primer y el último día del proyecto. El servicio cubrirá todo el periodo elegido.
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Información persistente al pie del formulario -->
                    <div class="reserva-info">
                        <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                        <span>La solicitud quedará <strong>pendiente</strong> hasta que el prestador la acepte.</span>
                    </div>

                    <!-- Mensaje de error en línea -->
                    <div class="alert alert-danger d-none mt-3 mb-0" id="reservaError" role="alert"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnReservaConfirmar">
                        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                        Confirmar reserva
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
/* ============================================================
   Reserva — formulario único con controles según unidad de cobro
   ============================================================ */
(function () {
    var formReserva = document.getElementById('formReserva');
    if (!formReserva) return;

    var modo            = formReserva.querySelector('input[name="modo_reserva"]').value;
    var inputFechaIni   = document.getElementById('reservaFechaServicio');
    var inputFechaFin   = document.getElementById('reservaFechaFin');
    var btnConfirmar    = document.getElementById('btnReservaConfirmar');
    var errorBox        = document.getElementById('reservaError');

    // Configuración común de flatpickr en español
    if (window.flatpickr && window.flatpickr.l10ns && window.flatpickr.l10ns.es) {
        flatpickr.localize(flatpickr.l10ns.es);
    }

    // Helpers
    function showError(msg) {
        if (!errorBox) return;
        errorBox.textContent = msg;
        errorBox.classList.remove('d-none');
    }
    function clearError() {
        if (!errorBox) return;
        errorBox.classList.add('d-none');
        errorBox.textContent = '';
    }
    function pad(n) { return (n < 10 ? '0' : '') + n; }
    function ymd(date) {
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
    }

    // ─────────────────────────────────────────────
    // Inicialización de pickers según el modo
    // ─────────────────────────────────────────────
    var fpDiaUnico, fpDiaHora, fpDiaSesion, fpRangoDias;

    if (modo === 'dia') {
        fpDiaUnico = flatpickr('#reservaDiaUnico', {
            dateFormat: 'Y-m-d',
            minDate:    'tomorrow',
            disableMobile: true
        });
    }
    else if (modo === 'hora') {
        fpDiaHora = flatpickr('#reservaDiaHora', {
            dateFormat: 'Y-m-d',
            minDate:    'tomorrow',
            disableMobile: true
        });
    }
    else if (modo === 'sesion' || modo === 'trabajo') {
        fpDiaSesion = flatpickr('#reservaDiaSesion', {
            dateFormat: 'Y-m-d',
            minDate:    'tomorrow',
            disableMobile: true
        });
    }
    else if (modo === 'proyecto') {
        fpRangoDias = flatpickr('#reservaRangoDias', {
            mode:       'range',
            dateFormat: 'Y-m-d',
            minDate:    'tomorrow',
            disableMobile: true
        });
    }

    // ─────────────────────────────────────────────
    // Validación al enviar el formulario
    // ─────────────────────────────────────────────
    formReserva.addEventListener('submit', function (e) {
        clearError();

        if (modo === 'dia') {
            var dia = document.getElementById('reservaDiaUnico').value;
            if (!dia) { e.preventDefault(); showError('Selecciona un día.'); return; }
            // Día completo: de 00:00:00 a 23:59:59
            inputFechaIni.value = dia + ' 00:00:00';
            inputFechaFin.value = dia + ' 23:59:59';
            return;
        }

        if (modo === 'hora') {
            var diaH    = document.getElementById('reservaDiaHora').value;
            var horaIni = document.getElementById('reservaHoraInicio').value;
            var horaFin = document.getElementById('reservaHoraFin').value;
            if (!diaH || !horaIni || !horaFin) {
                e.preventDefault();
                showError('Selecciona día, hora de inicio y hora de fin.');
                return;
            }
            if (horaFin <= horaIni) {
                e.preventDefault();
                showError('La hora de fin debe ser posterior a la de inicio.');
                return;
            }
            inputFechaIni.value = diaH + ' ' + horaIni + ':00';
            inputFechaFin.value = diaH + ' ' + horaFin + ':00';
            return;
        }

        if (modo === 'sesion' || modo === 'trabajo') {
            var diaS    = document.getElementById('reservaDiaSesion').value;
            var horaIni2 = document.getElementById('reservaHoraInicioMin').value;
            var horaFin2 = document.getElementById('reservaHoraFinMin').value;
            if (!diaS || !horaIni2 || !horaFin2) {
                e.preventDefault();
                showError('Completa día y franja horaria.');
                return;
            }
            if (horaFin2 <= horaIni2) {
                e.preventDefault();
                showError('La hora de fin debe ser posterior a la de inicio.');
                return;
            }
            inputFechaIni.value = diaS + ' ' + horaIni2 + ':00';
            inputFechaFin.value = diaS + ' ' + horaFin2 + ':00';
            return;
        }

        if (modo === 'proyecto') {
            if (!fpRangoDias || !fpRangoDias.selectedDates || fpRangoDias.selectedDates.length < 2) {
                e.preventDefault();
                showError('Selecciona el día de inicio y el día de fin del proyecto.');
                return;
            }
            var ini = fpRangoDias.selectedDates[0];
            var fin = fpRangoDias.selectedDates[1];
            inputFechaIni.value = ymd(ini) + ' 00:00:00';
            inputFechaFin.value = ymd(fin) + ' 23:59:59';
            return;
        }
    });

    // Resetear el formulario al cerrar el modal
    var modalEl = document.getElementById('modalReserva');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            clearError();
            formReserva.reset();
            inputFechaIni.value = '';
            inputFechaFin.value = '';
            if (fpDiaUnico)  fpDiaUnico.clear();
            if (fpDiaHora)   fpDiaHora.clear();
            if (fpDiaSesion) fpDiaSesion.clear();
            if (fpRangoDias) fpRangoDias.clear();
        });
    }
})();

/* ============================================================
   Galería de miniaturas: clic cambia imagen principal
   ============================================================ */
(function () {
    var principal = document.getElementById('servicioImagenPrincipal');
    var minis = document.querySelectorAll('.servicio-miniatura');
    if (!principal || !minis.length) return;

    minis.forEach(function (btn) {
        btn.addEventListener('click', function () {
            principal.src = this.dataset.url;
            minis.forEach(function (m) { m.classList.remove('activa'); });
            this.classList.add('activa');
        });
    });
})();
</script>


<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
</body>
</html>