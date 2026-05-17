<?php
session_start();
// vista/home.php
require_once "../conexion.php";
require_once "../controladores/usuarioController.php";
require_once "../controladores/servicioController.php";
require_once "../modelo/chatModelo.php"; // Necesario para el contador de mensajes

$servicioController = new ServicioController($conexion);
$servicios = $servicioController->buscar($_GET['buscar'] ?? '');

// Cargar la imagen portada (primera por orden) de TODOS los servicios mostrados
// en una sola query, para evitar N+1.
$portadas = [];
if (!empty($servicios)) {
    $ids = array_map(fn($s) => (int) $s['id'], $servicios);
    $marcadores = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conexion->prepare(
        "SELECT si.servicio_id, si.url_publica
         FROM servicio_imagenes si
         INNER JOIN (
             SELECT servicio_id, MIN(orden) AS min_orden
             FROM servicio_imagenes
             WHERE servicio_id IN ($marcadores)
             GROUP BY servicio_id
         ) primera ON primera.servicio_id = si.servicio_id
                  AND primera.min_orden  = si.orden"
    );
    $stmt->execute($ids);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $portadas[(int) $row['servicio_id']] = $row['url_publica'];
    }
}

// Lógica para el contador de mensajes no leídos
$chatModelo = new ChatModelo($conexion);
$usuario_id = $_SESSION['usuario_id'] ?? null;
$no_leidos = ($usuario_id) ? $chatModelo->getTotalNoLeidos($usuario_id) : 0;

// Notificaciones no leídas (badge en navbar)
$no_leidos_notif = 0;
if ($usuario_id) {
    $stmt = $conexion->prepare(
        "SELECT COUNT(*) FROM notificaciones_usuario
         WHERE usuario_destino_id = ? AND leida = 0"
    );
    $stmt->execute([$usuario_id]);
    $no_leidos_notif = (int) $stmt->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Marketplace – Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/estilo.css">
    <style>
        .badge-notify {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }
    </style>
</head>

<body class="bg-light">
<a class="skip-link" href="#contenido">Saltar al contenido principal</a>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation" aria-label="Principal">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>

        <div class="d-flex gap-2 ms-auto align-items-center">
            <a href="subirServicio.php" class="btn btn-success btn-sm">+ Ofrecer servicio</a>
            
            <form action="home.php" method="GET" class="d-flex mx-2">
                <input type="text" name="buscar" aria-label="Buscar servicios" class="form-control form-control-sm me-2" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>" style="width: 250px;">
                <button aria-label="Buscar" type="submit" class="btn btn-outline-light btn-sm"><i class="bi bi-search" aria-hidden="true"></i></button>
            </form>

            <a href="chat.php" class="btn btn-outline-light btn-sm position-relative">
                <i class="bi bi-chat-dots-fill" aria-hidden="true"></i> Mensajes
                <?php if ($no_leidos > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $no_leidos; ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="perfil.php?tab=notificaciones" class="btn btn-outline-light btn-sm position-relative">
                <i class="bi bi-bell-fill" aria-hidden="true"></i> Notificaciones
                <?php if ($no_leidos_notif > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $no_leidos_notif; ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="perfil.php" class="btn btn-outline-light btn-sm"><i class="bi bi-person-fill" aria-hidden="true"></i> Mi perfil</a>

            <?php if (isset($_SESSION['es_admin']) && $_SESSION['es_admin'] == 1): ?>
                <a href="panelAdministracion.php" class="btn btn-warning btn-sm"><i class="bi bi-wrench" aria-hidden="true"></i> Panel de administración</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div id="contenido" role="main" class="container mt-5">
    <h1 class="text-center mb-4">Servicios disponibles</h1>

    <div class="row">
        <?php foreach ($servicios as $servicio): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 servicio-card">
                    <a href="servicio.php?id=<?php echo (int) $servicio['id']; ?>" class="text-decoration-none">
                        <?php if (!empty($portadas[(int) $servicio['id']])): ?>
                            <img src="<?php echo htmlspecialchars($portadas[(int) $servicio['id']]); ?>"
                                 alt="<?php echo htmlspecialchars($servicio['titulo']); ?>"
                                 class="servicio-portada">
                        <?php else: ?>
                            <div class="servicio-portada-placeholder">
                                <i class="bi bi-image" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="servicio.php?id=<?php echo $servicio['id']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($servicio['titulo']); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars($servicio['descripcion']); ?>
                        </p>
                        <p class="fw-bold text-success">
                            <?php echo number_format($servicio['precio'], 2); ?> € /
                            <?php echo htmlspecialchars($servicio['unidad_cobro']); ?>
                        </p>
                        <p>
                            <span class="badge bg-secondary">
                                <?php echo htmlspecialchars($servicio['categoria_nombre']); ?>
                            </span>
                            <span class="badge bg-warning text-dark">
                                ⭐ <?php echo $servicio['valoracion_media']; ?>
                            </span>
                        </p>
                        <p class="text-muted small">
                            <a href="verUsuarios.php?id=<?php echo $servicio['prestador_id']; ?>">
                                Prestador: <?php echo htmlspecialchars($servicio['prestador']); ?>
                            </a>
                        </p>
                        <a href="servicio.php?id=<?php echo $servicio['id']; ?>"
                           class="btn btn-sm btn-outline-primary w-100">Ver servicio</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
</body>
</html>