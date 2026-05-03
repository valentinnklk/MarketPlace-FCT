<?php
require_once "../conexion.php";
require_once "../controladores/servicioController.php";
 
if (session_status() === PHP_SESSION_NONE) session_start();
 
$servicioController = new ServicioController($conexion);
$servicio = $servicioController->mostrarServicioPorId($_GET['id']);
 
$usuario_logueado = $_SESSION['usuario_id'] ?? null;
$es_propietario   = $usuario_logueado && $usuario_logueado == $servicio['prestador_id'];
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Servicio</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .servicio-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }
        .servicio-card:hover {
            transform: translateY(-5px);
        }
        .servicio-imagen {
            object-fit: cover;
            height: 300px;
            width: 100%;
        }
        .precio {
            font-size: 1.8rem;
            font-weight: bold;
            color: #28a745;
        }
        .badge-estado {
            font-size: 0.9rem;
        }
        .btn-accion {
            flex: 1;
        }
    </style>
</head>
<body class="bg-light">
 
<div class="container py-5">
    <?php if ($servicio): ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card servicio-card">
                    <!-- Imagen del servicio -->
                    <img src="<?php echo $servicio['imagen'] ?? 'https://via.placeholder.com/800x300.png?text=Servicio'; ?>" 
                         alt="<?php echo $servicio['titulo']; ?>" class="servicio-imagen">
                    
                    <div class="card-body p-4">
                        <h2 class="card-title mb-3"><?php echo $servicio['titulo']; ?></h2>
                        <p class="card-text text-muted mb-3"><?php echo $servicio['descripcion']; ?></p>
                        <p class="precio mb-3">$<?php echo number_format($servicio['precio'], 2); ?></p>
                        <span class="badge bg-secondary badge-estado mb-3"><?php echo $servicio['activo'] ? 'Activo' : 'Inactivo'; ?></span>
                        <p class="text-muted small mb-4">Vendido por: <?php echo $servicio['prestador']; ?></p>
 
                        <!-- Botones de acción -->
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="../index.php" class="btn btn-outline-primary btn-accion">Volver</a>
                            <button class="btn btn-success btn-accion">Agregar al Carrito</button>
                            <button class="btn btn-outline-warning btn-accion">Guardar en Favoritos</button>
                            <!-- Botón reportar -->
                            <?php if ($usuario_logueado && !$es_propietario): ?>
                                <button class="btn btn-outline-danger btn-accion" data-bs-toggle="modal" data-bs-target="#modalReporte">
                                    🚩 Reportar Servicio
                                </button>
                            <?php endif; ?>
 
                            <?php if ($usuario_logueado && !$es_propietario): ?>
                                <form method="POST" action="chat.php?accion=abrir" class="btn-accion">
                                    <input type="hidden" name="prestador_id" value="<?= (int) $servicio['prestador_id'] ?>">
                                    <input type="hidden" name="servicio_id"  value="<?= (int) $servicio['id'] ?>">
                                    <button type="submit" class="btn btn-primary w-100">💬 Contactar</button>
                                </form>
                            <?php elseif (!$usuario_logueado): ?>
                                <a href="loginVista.php" class="btn btn-outline-primary btn-accion">💬 Inicia sesión para contactar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            Servicio no encontrado.
        </div>
    <?php endif; ?>
</div>
 <!-- Modal de reporte -->
<?php if ($usuario_logueado && !$es_propietario): ?>
<div class="modal fade" id="modalReporte" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🚩 Reportar servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="../CONTROLADORES/reportarController.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="tipo" value="servicio">
                    <input type="hidden" name="servicio_id" value="<?php echo $servicio['id']; ?>">
                    <input type="hidden" name="usuario_reportado_id" value="">
                    <input type="hidden" name="redirect" value="../VISTA/servicio.php?id=<?php echo $servicio['id']; ?>">

                    <?php if (isset($_GET['reporte'])): ?>
                        <div class="alert <?php echo $_GET['reporte'] === 'ok' ? 'alert-success' : 'alert-danger'; ?>">
                            <?php echo $_GET['reporte'] === 'ok' ? '✅ Reporte enviado correctamente.' : '❌ El motivo no puede estar vacío.'; ?>
                        </div>
                    <?php endif; ?>

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
 