<?php
require_once "../controladores/productoController.php";

$productoController = new ProductoController($conexion);
$producto = $productoController->mostrarProductoPorId($_GET['id']);

?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Producto</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .producto-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: transform 0.3s;
        }
        .producto-card:hover {
            transform: translateY(-5px);
        }
        .producto-imagen {
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
    <?php if ($producto): ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card producto-card">
                    <!-- Imagen del producto -->
                    <img src="<?php echo $producto['imagen'] ?? 'https://via.placeholder.com/800x300.png?text=Producto'; ?>" 
                         alt="<?php echo $producto['titulo']; ?>" class="producto-imagen">
                    
                    <div class="card-body p-4">
                        <h2 class="card-title mb-3"><?php echo $producto['titulo']; ?></h2>
                        <p class="card-text text-muted mb-3"><?php echo $producto['descripcion']; ?></p>
                        <p class="precio mb-3">$<?php echo number_format($producto['precio'], 2); ?></p>
                        <span class="badge bg-secondary badge-estado mb-3"><?php echo $producto['estado_producto']; ?></span>
                        <p class="text-muted small mb-4">Vendido por: <?php echo $producto['vendedor']; ?></p>

                        <!-- Botones de acción -->
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="../index.php" class="btn btn-outline-primary btn-accion">Volver</a>
                            <button class="btn btn-success btn-accion">Agregar al Carrito</button>
                            <button class="btn btn-outline-warning btn-accion">Guardar en Favoritos</button>
                            <button class="btn btn-outline-danger btn-accion">Reportar Producto</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            Producto no encontrado.
        </div>
    <?php endif; ?>
</div>

</body>
</html>