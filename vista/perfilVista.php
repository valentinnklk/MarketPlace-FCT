<?php
// vista/perfilVista.php
// Variables que vienen del PerfilController:
// $usuario, $serviciosOfrecidos, $contratosCliente, $contratosPrestador,
// $favoritos, $valoraciones, $valoracionMedia
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil – <?php echo htmlspecialchars($usuario['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar-circulo {
            width: 70px; height: 70px; border-radius: 50%;
            background-color: #0d6efd; color: #fff;
            font-size: 1.6rem; font-weight: bold;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-pills .nav-link.active { background-color: #0d6efd; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .estrellas { color: #f5c518; }
    </style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
        <div class="d-flex gap-2">
            <a href="home.php"         class="btn btn-outline-light btn-sm">Inicio</a>
            <a href="subirServicio.php" class="btn btn-success btn-sm">+ Ofrecer servicio</a>
            <a href="perfil.php"       class="btn btn-outline-light btn-sm">👤 Mi perfil</a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <!-- CABECERA -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="avatar-circulo">
                    <?php echo strtoupper(substr($usuario['nombre'], 0, 2)); ?>
                </div>
                <div>
                    <h4 class="mb-0"><?php echo htmlspecialchars($usuario['nombre']); ?></h4>
                    <small class="text-muted">
                        <?php echo htmlspecialchars($usuario['ubicacion'] ?? ''); ?>
                        &nbsp;·&nbsp;
                        Miembro desde <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?>
                    </small>
                    <?php if ($usuario['es_administrador']): ?>
                        <span class="badge bg-danger ms-2">Admin</span>
                    <?php endif; ?>
                    <?php if ($valoracionMedia['total'] > 0): ?>
                        <div class="mt-1">
                            <span class="estrellas"><?php echo str_repeat('★', round($valoracionMedia['media'])) . str_repeat('☆', 5 - round($valoracionMedia['media'])); ?></span>
                            <small class="text-muted ms-1">
                                <?php echo number_format($valoracionMedia['media'], 1); ?> · <?php echo $valoracionMedia['total']; ?> valoracion<?php echo $valoracionMedia['total'] !== 1 ? 'es' : ''; ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ESTADÍSTICAS -->
            <div class="row text-center g-2">
                <div class="col-6 col-md-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold fs-5"><?php echo count($serviciosOfrecidos); ?></div>
                        <small class="text-muted">Servicios ofrecidos</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold fs-5"><?php echo count($contratosCliente); ?></div>
                        <small class="text-muted">Servicios contratados</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold fs-5"><?php echo count($favoritos); ?></div>
                        <small class="text-muted">Favoritos</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold fs-5"><?php echo count($valoraciones); ?></div>
                        <small class="text-muted">Valoraciones</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PESTAÑAS -->
    <?php $tabActiva = $_GET['tab'] ?? 'servicios'; ?>

    <ul class="nav nav-pills mb-3 flex-wrap gap-1">
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'servicios'     ? 'active' : ''; ?>" href="?tab=servicios">
                🛠️ Mis servicios
                <span class="badge bg-secondary"><?php echo count($serviciosOfrecidos); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'contratados'   ? 'active' : ''; ?>" href="?tab=contratados">
                📋 Contratados
                <span class="badge bg-secondary"><?php echo count($contratosCliente); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'recibidos'     ? 'active' : ''; ?>" href="?tab=recibidos">
                📥 Pedidos recibidos
                <span class="badge bg-secondary"><?php echo count($contratosPrestador); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'favoritos'     ? 'active' : ''; ?>" href="?tab=favoritos">
                ♡ Favoritos
                <span class="badge bg-secondary"><?php echo count($favoritos); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'valoraciones'  ? 'active' : ''; ?>" href="?tab=valoraciones">
                ⭐ Valoraciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'notificaciones' ? 'active' : ''; ?>" href="?tab=notificaciones">
                🔔 Notificaciones
                <?php if ($notificacionesNoLeidas > 0): ?>
                    <span class="badge bg-danger"><?php echo $notificacionesNoLeidas; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'configuracion' ? 'active' : ''; ?>" href="?tab=configuracion">
                ⚙️ Configuración
            </a>
        </li>
    </ul>

    <!-- ── MIS SERVICIOS (como prestador) ── -->
    <?php if ($tabActiva === 'servicios'): ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Mis servicios</h5>
            <a href="subirServicio.php" class="btn btn-success btn-sm">+ Nuevo servicio</a>
        </div>

        <?php if (empty($serviciosOfrecidos)): ?>
            <div class="alert alert-secondary text-center">
                No tienes ningún servicio publicado.
                <a href="subirServicio.php">¡Publica el primero!</a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($serviciosOfrecidos as $s): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <span class="badge bg-light text-dark mb-2"><?php echo htmlspecialchars($s['categoria']); ?></span>
                                <h6 class="card-title"><?php echo htmlspecialchars($s['titulo']); ?></h6>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars($s['descripcion']); ?></p>
                                <p class="fw-bold text-success mb-1">
                                    <?php echo number_format($s['precio'], 2); ?> €
                                    <small class="text-muted fw-normal">/ <?php echo $s['unidad_cobro']; ?></small>
                                </p>
                                <?php if ($s['valoracion_media'] > 0): ?>
                                    <span class="estrellas small">
                                        <?php echo str_repeat('★', round($s['valoracion_media'])) . str_repeat('☆', 5 - round($s['valoracion_media'])); ?>
                                    </span>
                                    <small class="text-muted"><?php echo number_format($s['valoracion_media'], 1); ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <a href="servicio.php?id=<?php echo $s['id']; ?>" class="btn btn-outline-primary btn-sm flex-fill">Ver</a>
                                <a href="editarServicio.php?id=<?php echo $s['id']; ?>" class="btn btn-outline-secondary btn-sm flex-fill">Editar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── SERVICIOS CONTRATADOS (como cliente) ── -->
    <?php elseif ($tabActiva === 'contratados'): ?>

        <h5 class="mb-3">Servicios que he contratado</h5>

        <?php if (empty($contratosCliente)): ?>
            <div class="alert alert-secondary text-center">No has contratado ningún servicio todavía.</div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($contratosCliente as $c):
                    $badge = match($c['estado']) {
                        'completado' => 'bg-success',
                        'en_proceso' => 'bg-primary',
                        'aceptado'   => 'bg-info text-dark',
                        'cancelado'  => 'bg-danger',
                        default      => 'bg-warning text-dark'
                    };
                ?>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($c['titulo']); ?></h6>
                                <small class="text-muted">
                                    Prestador: <b><?php echo htmlspecialchars($c['prestador']); ?></b>
                                    · Contrato #<?php echo $c['id']; ?>
                                    · <?php echo date('d/m/Y', strtotime($c['fecha_contrato'])); ?>
                                </small>
                                <div class="mt-1">
                                    <span class="badge <?php echo $badge; ?>"><?php echo ucfirst(str_replace('_', ' ', $c['estado'])); ?></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-5"><?php echo number_format($c['precio_acordado'], 2); ?> €</div>
                                <small class="text-muted"><?php echo $c['unidad_cobro']; ?></small>
                                <br>
                                <a href="servicio.php?id=<?php echo $c['servicio_id']; ?>" class="btn btn-outline-primary btn-sm mt-1">Ver servicio</a>

                                <?php if ($c['estado'] === 'completado'): ?>
                                    <?php if (!empty($c['reseña_id'])): ?>
                                        <span class="badge bg-success mt-1 d-inline-block">✓ Valorado</span>
                                    <?php else: ?>
                                        <a href="reseñaVista.php?contrato_id=<?php echo (int) $c['id']; ?>"
                                           class="btn btn-warning btn-sm mt-1">⭐ Valorar</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── PEDIDOS RECIBIDOS (como prestador) ── -->
    <?php elseif ($tabActiva === 'recibidos'): ?>

        <h5 class="mb-3">Pedidos recibidos</h5>

        <?php if (empty($contratosPrestador)): ?>
            <div class="alert alert-secondary text-center">Aún no has recibido ningún pedido.</div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($contratosPrestador as $c):
                    $badge = match($c['estado']) {
                        'completado' => 'bg-success',
                        'en_proceso' => 'bg-primary',
                        'aceptado'   => 'bg-info text-dark',
                        'cancelado'  => 'bg-danger',
                        default      => 'bg-warning text-dark'
                    };
                ?>
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($c['titulo']); ?></h6>
                                <small class="text-muted">
                                    Cliente: <b><?php echo htmlspecialchars($c['cliente']); ?></b>
                                    · Contrato #<?php echo $c['id']; ?>
                                    · <?php echo date('d/m/Y', strtotime($c['fecha_contrato'])); ?>
                                </small>
                                <div class="mt-1">
                                    <span class="badge <?php echo $badge; ?>"><?php echo ucfirst(str_replace('_', ' ', $c['estado'])); ?></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-5"><?php echo number_format($c['precio_acordado'], 2); ?> €</div>
                                <small class="text-muted"><?php echo $c['unidad_cobro']; ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── FAVORITOS ── -->
    <?php elseif ($tabActiva === 'favoritos'): ?>

        <h5 class="mb-3">Servicios guardados</h5>

        <?php if (empty($favoritos)): ?>
            <div class="alert alert-secondary text-center">No tienes ningún servicio guardado.</div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($favoritos as $f): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <?php if (!$f['activo']): ?>
                                <div class="card-header bg-secondary text-white text-center small">No disponible</div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($f['titulo']); ?></h6>
                                <p class="fw-bold text-success mb-1">
                                    <?php echo number_format($f['precio'], 2); ?> €
                                    <small class="text-muted fw-normal">/ <?php echo $f['unidad_cobro']; ?></small>
                                </p>
                                <small class="text-muted">Prestador: <?php echo htmlspecialchars($f['prestador']); ?></small>
                                <br>
                                <small class="text-muted">❤️ Guardado el <?php echo date('d/m/Y', strtotime($f['fecha_agregado'])); ?></small>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <a href="servicio.php?id=<?php echo $f['id']; ?>" class="btn btn-outline-primary btn-sm flex-fill">Ver</a>
                                <form method="POST" action="perfil.php?accion=eliminarFavorito" class="flex-fill">
                                    <input type="hidden" name="servicio_id" value="<?php echo $f['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">✕ Quitar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── VALORACIONES ── -->
    <?php elseif ($tabActiva === 'valoraciones'): ?>

        <div class="d-flex align-items-center gap-3 mb-3">
            <h5 class="mb-0">Valoraciones recibidas</h5>
            <?php if ($valoracionMedia['total'] > 0): ?>
                <span class="estrellas fs-5"><?php echo str_repeat('★', round($valoracionMedia['media'])) . str_repeat('☆', 5 - round($valoracionMedia['media'])); ?></span>
                <span class="text-muted"><?php echo number_format($valoracionMedia['media'], 1); ?> de media · <?php echo $valoracionMedia['total']; ?> valoracion<?php echo $valoracionMedia['total'] !== 1 ? 'es' : ''; ?></span>
            <?php endif; ?>
        </div>

        <?php if (empty($valoraciones)): ?>
            <div class="alert alert-secondary text-center">Todavía no tienes valoraciones.</div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($valoraciones as $v): ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold"><?php echo htmlspecialchars($v['autor']); ?></span>
                                <div>
                                    <span class="estrellas">
                                        <?php echo str_repeat('★', $v['puntuacion']) . str_repeat('☆', 5 - $v['puntuacion']); ?>
                                    </span>
                                    <small class="text-muted ms-2"><?php echo date('d/m/Y', strtotime($v['fecha'])); ?></small>
                                </div>
                            </div>
                            <p class="mb-1"><?php echo htmlspecialchars($v['comentario'] ?? ''); ?></p>
                            <small class="text-muted">🛠️ <?php echo htmlspecialchars($v['servicio']); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── CONFIGURACIÓN ── -->
    <?php elseif ($tabActiva === 'configuracion'): ?>

        <h5 class="mb-3">Configuración de la cuenta</h5>

        <div class="card mb-3">
            <div class="card-header fw-bold">👤 Datos personales</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Nombre</span>
                    <span class="text-muted"><?php echo htmlspecialchars($usuario['nombre']); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Email</span>
                    <span class="text-muted"><?php echo htmlspecialchars($usuario['email']); ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Ubicación</span>
                    <span class="text-muted"><?php echo htmlspecialchars($usuario['ubicacion'] ?? '—'); ?></span>
                </li>
                <?php if ($usuario['tiempo_respuesta']): ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Tiempo de respuesta medio</span>
                    <span class="text-muted"><?php echo $usuario['tiempo_respuesta']; ?> h</span>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="card border-danger">
            <div class="card-header text-danger fw-bold">⚠️ Zona de peligro</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Esta acción es irreversible.</p>
                <button class="btn btn-outline-danger btn-sm">Eliminar mi cuenta</button>
            </div>
        </div>

    <!-- ── NOTIFICACIONES ── -->
    <?php elseif ($tabActiva === 'notificaciones'): ?>

        <h5 class="mb-3">🔔 Notificaciones</h5>

        <?php if (empty($notificaciones)): ?>
            <div class="alert alert-secondary text-center">No tienes notificaciones todavía.</div>
        <?php else: ?>
            <div class="d-flex flex-column gap-2">
                <?php foreach ($notificaciones as $n):
                    $iconos = [
                        'nueva_reserva'        => '📅',
                        'reserva_aceptada'     => '✅',
                        'reserva_rechazada'    => '❌',
                        'servicio_finalizado'  => '🏁',
                        'solicitud_aprobada'   => '👍',
                        'solicitud_rechazada'  => '👎',
                        'contrato_actualizado' => '📝',
                    ];
                    $icono = $iconos[$n['tipo']] ?? '🔔';

                    $contrato_id_notif     = null;
                    $conversacion_id_notif = null;
                    if (preg_match('/#contrato:(\d+)/', $n['mensaje'], $m))      $contrato_id_notif     = (int) $m[1];
                    if (preg_match('/#conversacion:(\d+)/', $n['mensaje'], $m))  $conversacion_id_notif = (int) $m[1];

                    $mensajeLimpio = preg_replace('/\s*#(contrato|conversacion):\d+\s*/', '', $n['mensaje']);

                    $clase = $n['leida'] ? 'border' : 'border border-primary';
                    $bg    = $n['leida'] ? 'bg-white' : 'bg-light';
                ?>
                    <div class="card <?php echo $clase; ?> <?php echo $bg; ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <?php echo $icono; ?>
                                        <?php echo htmlspecialchars($n['titulo']); ?>
                                        <?php if (!$n['leida']): ?>
                                            <span class="badge bg-primary ms-1">Nueva</span>
                                        <?php endif; ?>
                                    </h6>
                                    <p class="mb-1 text-muted small"><?php echo htmlspecialchars($mensajeLimpio); ?></p>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($n['fecha_envio'])); ?>
                                    </small>
                                </div>
                            </div>

                            <?php if ($n['tipo'] === 'nueva_reserva' && !$n['leida'] && $contrato_id_notif): ?>
                                <div class="mt-3 d-flex gap-2">
                                    <form method="POST" action="../controladores/responder_reserva.php" class="flex-fill">
                                        <input type="hidden" name="contrato_id"     value="<?php echo $contrato_id_notif; ?>">
                                        <input type="hidden" name="notificacion_id" value="<?php echo (int) $n['id']; ?>">
                                        <input type="hidden" name="accion"          value="aceptar">
                                        <button type="submit" class="btn btn-success btn-sm w-100">✅ Aceptar</button>
                                    </form>
                                    <form method="POST" action="../controladores/responder_reserva.php" class="flex-fill">
                                        <input type="hidden" name="contrato_id"     value="<?php echo $contrato_id_notif; ?>">
                                        <input type="hidden" name="notificacion_id" value="<?php echo (int) $n['id']; ?>">
                                        <input type="hidden" name="accion"          value="rechazar">
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">❌ Rechazar</button>
                                    </form>
                                </div>
                            <?php elseif ($n['tipo'] === 'reserva_aceptada' && $conversacion_id_notif): ?>
                                <div class="mt-2">
                                    <a href="chat.php?chat_id=<?php echo $conversacion_id_notif; ?>"
                                       class="btn btn-outline-primary btn-sm">💬 Ir al chat</a>
                                </div>
                            <?php elseif ($n['tipo'] === 'servicio_finalizado' && $contrato_id_notif): ?>
                                <div class="mt-2">
                                    <a href="reseñaVista.php?contrato_id=<?php echo $contrato_id_notif; ?>"
                                       class="btn btn-warning btn-sm">⭐ Dejar valoración</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>