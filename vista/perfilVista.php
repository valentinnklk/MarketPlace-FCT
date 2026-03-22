<?php
// vista/perfilVista.php
// Vista HTML del perfil. Solo muestra datos.
// Las variables vienen del PerfilController:
// $usuario, $enVenta, $compras, $favoritos, $resenas
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

<!-- NAVBAR igual que home.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
        <div class="d-flex gap-2">
            <a href="home.php"          class="btn btn-outline-light btn-sm">Inicio</a>
            <a href="subirProducto.php" class="btn btn-success btn-sm">+ Vender</a>
            <a href="perfil.php"        class="btn btn-outline-light btn-sm">👤 Mi perfil</a>
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
                </div>
            </div>

            <!-- ESTADÍSTICAS -->
            <div class="row text-center g-2">
                <div class="col-6 col-md-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold fs-5"><?php echo count($enVenta); ?></div>
                        <small class="text-muted">En venta</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="border rounded p-2">
                        <div class="fw-bold fs-5"><?php echo count($compras); ?></div>
                        <small class="text-muted">Compras</small>
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
                        <div class="fw-bold fs-5"><?php echo count($resenas); ?></div>
                        <small class="text-muted">Reseñas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PESTAÑAS -->
    <?php $tabActiva = $_GET['tab'] ?? 'anuncios'; ?>

    <ul class="nav nav-pills mb-3 flex-wrap gap-1">
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'anuncios'      ? 'active' : ''; ?>" href="?tab=anuncios">
                🏷️ Mis anuncios
                <span class="badge bg-secondary"><?php echo count($enVenta); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'compras'       ? 'active' : ''; ?>" href="?tab=compras">
                🛒 Compras
                <span class="badge bg-secondary"><?php echo count($compras); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'favoritos'     ? 'active' : ''; ?>" href="?tab=favoritos">
                ♡ Favoritos
                <span class="badge bg-secondary"><?php echo count($favoritos); ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'resenas'       ? 'active' : ''; ?>" href="?tab=resenas">
                ⭐ Reseñas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $tabActiva === 'configuracion' ? 'active' : ''; ?>" href="?tab=configuracion">
                ⚙️ Configuración
            </a>
        </li>
    </ul>

    <!-- ── ANUNCIOS ── -->
    <?php if ($tabActiva === 'anuncios'): ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Mis productos en venta</h5>
            <a href="subirProducto.php" class="btn btn-success btn-sm">+ Nuevo anuncio</a>
        </div>

        <?php if (empty($enVenta)): ?>
            <div class="alert alert-secondary text-center">
                No tienes ningún producto en venta.
                <a href="subirProducto.php">¡Publica el primero!</a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($enVenta as $p): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title"><?php echo htmlspecialchars($p['titulo']); ?></h6>
                                <p class="card-text text-muted small"><?php echo htmlspecialchars($p['descripcion']); ?></p>
                                <p class="fw-bold text-success mb-1"><?php echo number_format($p['precio'], 2); ?> €</p>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($p['estado_producto']); ?></span>
                                <span class="badge bg-light text-dark">👁 <?php echo $p['visitas']; ?></span>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <a href="producto.php?id=<?php echo $p['id']; ?>" class="btn btn-outline-primary btn-sm flex-fill">Ver</a>
                                <button class="btn btn-outline-secondary btn-sm flex-fill">Editar</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── COMPRAS ── -->
    <?php elseif ($tabActiva === 'compras'): ?>

        <h5 class="mb-3">Mis compras</h5>

        <?php if (empty($compras)): ?>
            <div class="alert alert-secondary text-center">No has realizado ninguna compra todavía.</div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($compras as $c):
                    $badge = match($c['estado']) {
                        'completado' => 'bg-success',
                        'enviado'    => 'bg-primary',
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
                                    Vendedor: <b><?php echo htmlspecialchars($c['vendedor']); ?></b>
                                    · Pedido #<?php echo $c['id']; ?>
                                    · <?php echo date('d/m/Y', strtotime($c['fecha_creacion'])); ?>
                                </small>
                                <div class="mt-1">
                                    <span class="badge <?php echo $badge; ?>"><?php echo ucfirst($c['estado']); ?></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-5"><?php echo number_format($c['precio_final'], 2); ?> €</div>
                                <a href="producto.php?id=<?php echo $c['producto_id']; ?>" class="btn btn-outline-primary btn-sm mt-1">Ver producto</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── FAVORITOS ── -->
    <?php elseif ($tabActiva === 'favoritos'): ?>

        <h5 class="mb-3">Productos guardados</h5>

        <?php if (empty($favoritos)): ?>
            <div class="alert alert-secondary text-center">No tienes ningún producto guardado.</div>
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
                                <p class="fw-bold text-success mb-1"><?php echo number_format($f['precio'], 2); ?> €</p>
                                <small class="text-muted">Vendedor: <?php echo htmlspecialchars($f['vendedor']); ?></small>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <a href="producto.php?id=<?php echo $f['id']; ?>" class="btn btn-outline-primary btn-sm flex-fill">Ver</a>
                                <form method="POST" action="perfil.php?accion=eliminarFavorito" class="flex-fill">
                                    <input type="hidden" name="producto_id" value="<?php echo $f['id']; ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">✕ Quitar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <!-- ── RESEÑAS ── -->
    <?php elseif ($tabActiva === 'resenas'): ?>

        <h5 class="mb-3">Reseñas recibidas</h5>

        <?php if (empty($resenas)): ?>
            <div class="alert alert-secondary text-center">Todavía no tienes reseñas.</div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($resenas as $r): ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold"><?php echo htmlspecialchars($r['autor']); ?></span>
                                <div>
                                    <span class="estrellas">
                                        <?php echo str_repeat('★', $r['puntuacion']) . str_repeat('☆', 5 - $r['puntuacion']); ?>
                                    </span>
                                    <small class="text-muted ms-2"><?php echo date('d/m/Y', strtotime($r['fecha'])); ?></small>
                                </div>
                            </div>
                            <p class="mb-1"><?php echo htmlspecialchars($r['comentario']); ?></p>
                            <small class="text-muted">🏷️ <?php echo htmlspecialchars($r['producto']); ?></small>
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
            </ul>
        </div>

        <div class="card border-danger">
            <div class="card-header text-danger fw-bold">⚠️ Zona de peligro</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Esta acción es irreversible.</p>
                <button class="btn btn-outline-danger btn-sm">Eliminar mi cuenta</button>
            </div>
        </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
