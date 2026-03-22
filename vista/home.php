<?php
// vista/home.php
// Igual que tu home.php original pero con navbar actualizada
// para acceder al perfil y subir producto.

require_once "../conexion.php";
require_once "../controladores/usuarioController.php";
require_once "../controladores/productoController.php";

$productoController = new ProductoController($conexion);
$productos = $productoController->mostrarProducto();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Marketplace – Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR con acceso a perfil y subir producto -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>

        <div class="d-flex gap-2 ms-auto">
            <a href="subirProducto.php" class="btn btn-success btn-sm">+ Vender</a>
            <a href="perfil.php"        class="btn btn-outline-light btn-sm">👤 Mi perfil</a>
            <a href="panelAdministracion.php" class="btn btn-warning btn-sm">Admin</a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <h1 class="text-center mb-4">Productos</h1>

    <div class="row">
        <?php foreach ($productos as $producto): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <button class="btn btn-sm btn-outline-primary">
                                <a href="producto.php?id=<?php echo $producto['id']; ?>">
                                    <?php echo $producto['titulo']; ?>
                                </a>
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
                            <a href="verUsuarios.php?id=<?php echo $producto['vendedor_id']; ?>">
                                Vendedor: <?php echo $producto['vendedor']; ?>
                            </a>
                        </p>
                        <form method="post" action="">
                            <input type="hidden" name="product" value="<?php echo $producto['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-success">Agregar al Carrito</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>