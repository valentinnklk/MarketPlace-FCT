<?php
Require_once "../controladores/usuarioController.php";
require_once "../controladores/productoController.php";
$productoController = new ProductoController($conexion);
$productos = $productoController->mostrarProducto();

?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <h1 class="text-center mb-4">Productos</h1>

    <div class="row">

        <?php foreach ($productos as $producto): ?>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                        <button class="btn btn-sm btn-outline-primary">
                            <a href="producto.php?id=<?php echo $producto['id']; ?>"> <?php echo $producto['titulo']; ?> </a>
                        </button>
                                
                            </h5>
                        <p class="card-text text-muted">
                            <?php echo $producto['descripcion']; ?>
                        </p>
                        <p class="fw-bold text-success">
                            $<?php echo $producto['precio']; ?>
                        </p>
                        <p>
                            <span class="badge bg-secondary">
                                <?php echo $producto['estado_producto']; ?>
                            </span>
                        </p>
                        <p class="text-muted small">
                            Vendedor: <?php echo $producto['vendedor']; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>