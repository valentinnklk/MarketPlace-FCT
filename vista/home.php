<?php
// vista/home.php
session_start();
require_once "../conexion.php";
require_once "../controladores/usuarioController.php";
require_once "../controladores/servicioController.php";

$servicioController = new ServicioController($conexion);
$servicios = $servicioController->buscar($_GET['buscar'] ?? '');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Marketplace – Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>

        <div class="d-flex gap-2 ms-auto">
            <a href="subirServicio.php" class="btn btn-success btn-sm">+ Ofrecer servicio</a>
            <form action="home.php" method="GET" class="d-flex mx-auto">
            <input type="text" name="buscar" class="form-control form-control-sm me-2" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>"style="width: 300px;">
            <button type="submit" class="btn btn-outline-light btn-sm">🔍</button>
        </form>
            <a href="perfil.php"        class="btn btn-outline-light btn-sm">👤 Mi perfil</a>
            <?php if (isset($_SESSION['es_admin']) && $_SESSION['es_admin'] == 1): ?>
            <a href="panelAdministracion.php" class="btn btn-warning btn-sm">🔧 Panel de administración</a>
             <?php endif; ?>
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

<a href="../controladores/logout.php">Cerrar sesión</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
