<?php
Require_once "../controladores/usuarioController.php";
require_once "../controladores/productoController.php";
$productoController = new ProductoController($conexion);
$productos = $productoController->mostrarProducto();
$usuario = new Usuario($conexion);
$resultado = $usuario->mostrarUsuario();
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>Usuarios</title>
</head>
<body>

   
    <h1>Productos</h1>
    <ul>


    
        <?php foreach ($productos as $producto): ?>
            <li>
                <h2><?php echo $producto['titulo']; ?></h2>
                <p><?php echo $producto['descripcion']; ?></p>
                <p>Precio: $<?php echo $producto['precio']; ?></p>
                <p>Estado: <?php echo $producto['estado_producto']; ?></p>
                <p>Vendedor: <?php echo $producto['vendedor']; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

</body>
</html> 