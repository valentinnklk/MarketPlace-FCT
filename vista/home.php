<?php
// vista/home.php
require_once "../controladores/proteger.php";
require_once "../conexion.php";
require_once "../controladores/usuarioController.php";
require_once "../controladores/servicioController.php";
require_once "../modelo/chatModelo.php"; // Necesario para el contador de mensajes

$servicioController = new ServicioController($conexion);
$servicios = $servicioController->buscar($_GET['buscar'] ?? '');

// Lógica para el contador de mensajes no leídos
$chatModelo = new ChatModelo($conexion);
$usuario_id = $_SESSION['usuario_id'] ?? null;
$no_leidos = ($usuario_id) ? $chatModelo->getTotalNoLeidos($usuario_id) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Marketplace – Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>

        <div class="d-flex gap-2 ms-auto align-items-center">
            <a href="subirServicio.php" class="btn btn-success btn-sm">+ Ofrecer servicio</a>
            
            <form action="home.php" method="GET" class="d-flex mx-2">
                <input type="text" name="buscar" class="form-control form-control-sm me-2" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>" style="width: 250px;">
                <button type="submit" class="btn btn-outline-light btn-sm">🔍</button>
            </form>

            <a href="chat.php" class="btn btn-outline-light btn-sm position-relative">
                💬 Mensajes
                <?php if ($no_leidos > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $no_leidos; ?>
                    </span>
                <?php endif; ?>
            </a>

            <a href="perfil.php" class="btn btn-outline-light btn-sm">👤 Mi perfil</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center mb-4">Servicios disponibles</h1>

    <div class="row">
        <?php foreach ($servicios as $servicio): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
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

<div class="container text-center py-4">
    <a href="../controladores/logout.php" class="btn btn-link text-danger">Cerrar sesión</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>