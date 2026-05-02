<?php
session_start();
if (!isset($_SESSION['es_admin']) || !$_SESSION['es_admin']) {
    header("Location: home.php");
    exit();
}

require_once "../conexion.php";
require_once "../MODELO/reportesModelo.php";

$estadisticas     = obtenerEstadisticasReportes();
$reportesServicios = obtenerReportesServicios();
$reportesUsuarios  = obtenerReportesUsuarios();

// Totales para las tarjetas
$totales = ['pendiente' => 0, 'resuelto' => 0, 'rechazado' => 0];
foreach ($estadisticas as $e) {
    if (isset($totales[$e['estado']])) {
        $totales[$e['estado']] = $e['total'];
    }
}
$totalGeneral = array_sum($totales);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand fw-bold">⚙️ Panel de Administración</span>
    <a href="home.php" class="btn btn-outline-light btn-sm">← Volver al Home</a>
</nav>

<div class="container mt-4">

    <?php if (isset($_GET['respuestaCreacionUsu'])): ?>
        <div class="alert <?php echo $_GET['creacion'] == 'exito' ? 'alert-success' : 'alert-danger'; ?>">
            <?php echo htmlspecialchars($_GET['respuestaCreacionUsu']); ?>
        </div>
    <?php endif; ?>

    <h4 class="mb-3">📊 Estadísticas de reportes</h4>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-secondary text-center">
                <div class="card-body">
                    <h2><?php echo $totalGeneral; ?></h2>
                    <p class="mb-0">Total reportes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning text-center">
                <div class="card-body">
                    <h2><?php echo $totales['pendiente']; ?></h2>
                    <p class="mb-0">Pendientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success text-center">
                <div class="card-body">
                    <h2><?php echo $totales['resuelto']; ?></h2>
                    <p class="mb-0">Resueltos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger text-center">
                <div class="card-body">
                    <h2><?php echo $totales['rechazado']; ?></h2>
                    <p class="mb-0">Rechazados</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">🛑 Servicios denunciados</h4>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Servicio</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reportesServicios)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No hay servicios reportados</td></tr>
                <?php else: ?>
                    <?php foreach ($reportesServicios as $r): ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($r['motivo']); ?></td>
                            <td>
                                <?php
                                $badges = ['pendiente' => 'warning', 'resuelto' => 'success', 'rechazado' => 'danger'];
                                $badge = $badges[$r['estado']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $badge; ?>"><?php echo $r['estado']; ?></span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($r['fecha_creacion'])); ?></td>
                            <td>
                                <a href="servicio.php?id=<?php echo $r['servicio_id']; ?>"
                                   class="btn btn-sm btn-outline-primary">Ver servicio</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== USUARIOS REPORTADOS ===== -->
    <h4 class="mb-3">👤 Usuarios reportados</h4>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reportesUsuarios)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No hay usuarios reportados</td></tr>
                <?php else: ?>
                    <?php foreach ($reportesUsuarios as $r): ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['usuario_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($r['motivo']); ?></td>
                            <td>
                                <?php
                                $badges = ['pendiente' => 'warning', 'resuelto' => 'success', 'rechazado' => 'danger'];
                                $badge = $badges[$r['estado']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $badge; ?>"><?php echo $r['estado']; ?></span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($r['fecha_creacion'])); ?></td>
                            <td>
                                <a href="verUsuarios.php?id=<?php echo $r['usuario_id']; ?>"
                                   class="btn btn-sm btn-outline-primary">Ver usuario</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== FORMULARIOS CREAR USUARIOS ===== -->
    <h4 class="mb-3">➕ Crear usuarios</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4 mb-4">
                <h5>Usuario normal</h5>
                <form action="../CONTROLADORES/panelAdministracionControlador.php" method="POST">
                    <input type="hidden" name="tipo" value="normal">
                    <div class="mb-2"><input type="text" name="nombre" class="form-control" placeholder="Nombre" required></div>
                    <div class="mb-2"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                    <div class="mb-2"><input type="password" name="password" class="form-control" placeholder="Contraseña" required></div>
                    <div class="mb-2"><input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" required></div>
                    <button type="submit" name="usuarioNormal" class="btn btn-success w-100">Crear usuario</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 mb-4">
                <h5>Administrador</h5>
                <form action="../CONTROLADORES/panelAdministracionControlador.php" method="POST">
                    <input type="hidden" name="tipo" value="admin">
                    <div class="mb-2"><input type="text" name="nombre" class="form-control" placeholder="Nombre" required></div>
                    <div class="mb-2"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                    <div class="mb-2"><input type="password" name="password" class="form-control" placeholder="Contraseña" required></div>
                    <div class="mb-2"><input type="text" name="ubicacion" class="form-control" placeholder="Ubicación" required></div>
                    <button type="submit" name="usuarioAdmin" class="btn btn-danger w-100">Crear administrador</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>