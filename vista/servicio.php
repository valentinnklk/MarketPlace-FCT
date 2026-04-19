<?php
require_once "../conexion.php";
require_once "../controladores/servicioController.php";

$servicioController = new ServicioController($conexion);
$servicio = $servicioController->mostrarServicioPorId($_GET['id']);

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
                            <button class="btn btn-outline-danger btn-accion">Reportar Servicio</button>
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

</body>
</html>