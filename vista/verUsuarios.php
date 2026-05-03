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
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($usuario['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
    <a href="javascript:history.back()" class="btn btn-outline-light btn-sm">← Volver</a>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Tarjeta perfil -->
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex align-items-center gap-4">
                    <img src="<?php echo $usuario['avatar_url'] ? htmlspecialchars($usuario['avatar_url']) : 'https://ui-avatars.com/api/?name=' . urlencode($usuario['nombre']) . '&size=100&background=6c757d&color=fff'; ?>"
                         alt="Avatar" class="rounded-circle" style="width:100px;height:100px;object-fit:cover;">
                    <div>
                        <h3 class="mb-1"><?php echo htmlspecialchars($usuario['nombre']); ?></h3>
                        <p class="text-muted mb-1">📍 <?php echo htmlspecialchars($usuario['ubicacion']); ?></p>
                        <p class="text-muted mb-1">📧 <?php echo htmlspecialchars($usuario['email']); ?></p>
                        <p class="text-muted mb-1">📅 Miembro desde <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></p>
                        <?php if ($usuario['tiempo_respuesta']): ?>
                            <p class="text-muted mb-0">⚡ Tiempo de respuesta: <?php echo $usuario['tiempo_respuesta']; ?>h</p>
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
                        🚩 Reportar usuario
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
<div class="modal fade" id="modalReporteUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🚩 Reportar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../controladores/reportarControlador.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tipo" value="usuario">
                    <input type="hidden" name="servicio_id" value="">
                    <input type="hidden" name="usuario_reportado_id" value="<?php echo $usuario['id']; ?>">
                    <input type="hidden" name="redirect" value="../VISTA/verUsuarios.php?id=<?php echo $usuario['id']; ?>">

                    <?php if (isset($_GET['reporte'])): ?>
                        <div class="alert <?php echo $_GET['reporte'] === 'ok' ? 'alert-success' : 'alert-danger'; ?>">
                            <?php echo $_GET['reporte'] === 'ok' ? '✅ Reporte enviado.' : '❌ El motivo no puede estar vacío.'; ?>
                        </div>
                    <?php endif; ?>

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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Enviar reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>