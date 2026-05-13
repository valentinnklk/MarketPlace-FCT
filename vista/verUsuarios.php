<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once "../conexion.php";
require_once "../controladores/usuarioController.php";

$usuarioController = new UsuarioController($conexion);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$usuario  = $usuarioController->obtenerPorId($id);
$servicios = $usuarioController->obtenerServiciosPorUsuario($id);

if (!$usuario) {
    echo "<div class='alert alert-warning text-center mt-5'>Usuario no encontrado.</div>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($usuario['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body class="bg-light">
<a class="skip-link" href="#contenido">Saltar al contenido principal</a>

<nav class="navbar navbar-dark bg-dark px-4" role="navigation" aria-label="Principal">
    <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
    <a href="javascript:history.back()" class="btn btn-outline-light btn-sm">← Volver</a>
</nav>
<?php if (isset($_GET['reporte'])): ?>
    <div class="alert <?php echo $_GET['reporte'] === 'ok' ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show m-3" role="alert">
        <?php echo $_GET['reporte'] === 'ok' 
            ? '<i class="bi bi-check-circle-fill"></i> Reporte enviado correctamente.' 
            : '<i class="bi bi-x-circle-fill"></i> El motivo no puede estar vacío.'; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <script>
        setTimeout(() => document.querySelector('.alert')?.remove(), 3000);
    </script>
<?php endif; ?>
<div id="contenido" role="main" class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Tarjeta perfil -->
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex align-items-center gap-4">
                    <img src="<?php echo $usuario['avatar_url'] ? htmlspecialchars($usuario['avatar_url']) : 'https://ui-avatars.com/api/?name=' . urlencode($usuario['nombre']) . '&size=100&background=6c757d&color=fff'; ?>"
                         alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                    <div>
                        <h3 class="mb-1"><?php echo htmlspecialchars($usuario['nombre']); ?></h3>
                        <p class="text-muted mb-1"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> <?php echo htmlspecialchars($usuario['ubicacion']); ?></p>
                        <p class="text-muted mb-1"><i class="bi bi-envelope-fill" aria-hidden="true"></i> <?php echo htmlspecialchars($usuario['email']); ?></p>
                        <p class="text-muted mb-1"><i class="bi bi-calendar-event" aria-hidden="true"></i> Miembro desde <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></p>
                        <?php if ($usuario['tiempo_respuesta']): ?>
                            <p class="text-muted mb-0"><i class="bi bi-lightning-charge-fill" aria-hidden="true"></i> Tiempo de respuesta: <?php echo $usuario['tiempo_respuesta']; ?>h</p>
                        <?php endif; ?>
                        <?php if ($usuario['es_administrador']): ?>
                            <span class="badge bg-danger mt-2">Administrador</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Botón reportar usuario -->
            <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] != $id): ?>
                <div class="mb-4">
                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalReporteUsuario">
                        <i class="bi bi-flag-fill" aria-hidden="true"></i> Reportar usuario
                    </button>
                </div>
            <?php endif; ?>

            <!-- Servicios del usuario -->
            <h5 class="mb-3">Servicios ofrecidos</h5>
            <?php if (empty($servicios)): ?>
                <p class="text-muted">Este usuario no tiene servicios activos.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($servicios as $s): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($s['titulo']); ?></h6>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($s['descripcion']); ?></p>
                                    <p class="fw-bold text-success mb-2"><?php echo number_format($s['precio'], 2); ?> € / <?php echo htmlspecialchars($s['unidad_cobro']); ?></p>
                                    <a href="servicio.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary w-100">Ver servicio</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Modal reportar usuario -->
<?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] != $id): ?>
<div class="modal fade" id="modalReporteUsuario" tabindex="-1" role="dialog" aria-modal="true" tabindex="-1">
    <div class="modal-dialog" role="dialog" aria-modal="true" tabindex="-1">
        <div class="modal-content" role="dialog" aria-modal="true" tabindex="-1">
            <div class="modal-header" role="dialog" aria-modal="true" tabindex="-1">
                <h5 class="modal-title"><i class="bi bi-flag-fill" aria-hidden="true"></i> Reportar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../controladores/reportarController.php" method="POST">
                <div class="modal-body" role="dialog" aria-modal="true" tabindex="-1">
                    <input type="hidden" name="tipo" value="usuario">
                    <input type="hidden" name="servicio_id" value="">
                    <input type="hidden" name="usuario_reportado_id" value="<?php echo $usuario['id']; ?>">
                    <input type="hidden" name="redirect" value="../VISTA/verUsuarios.php?id=<?php echo $usuario['id']; ?>">

                    <label class="form-label">Motivo del reporte</label>
                    <select name="motivo" class="form-select" required>
                        <option value="">-- Selecciona un motivo --</option>
                        <option value="Comportamiento inapropiado">Comportamiento inapropiado</option>
                        <option value="Estafa o fraude">Estafa o fraude</option>
                        <option value="Spam">Spam</option>
                        <option value="Perfil falso">Perfil falso</option>
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

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
</body>
</html>