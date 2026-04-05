<?php
// vista/subirProductoVista.php
// Vista HTML del formulario de subir producto.
// Variables que vienen del SubirProductoController:
// $categorias (array), $errores (array)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir producto – Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- NAVBAR igual que home.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
        <div class="d-flex gap-2">
            <a href="home.php"   class="btn btn-outline-light btn-sm">Inicio</a>
            <a href="perfil.php" class="btn btn-outline-light btn-sm">👤 Mi perfil</a>
        </div>
    </div>
</nav>

<div class="container mt-4" style="max-width: 680px;">

    <h4 class="mb-4">Publicar producto</h4>

    <!-- ERRORES -->
    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- El form envía a subirProducto.php?accion=guardar -->
    <form method="POST" action="subirProducto.php?accion=guardar">

        <!-- DESCRIPCIÓN -->
        <div class="card mb-3">
            <div class="card-header fw-bold">✏️ Descripción</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Título <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="titulo" class="form-control"
                           maxlength="200"
                           placeholder="Ej: iPhone 12 128GB negro, como nuevo"
                           value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>"
                           required>
                </div>
                <div>
                    <label class="form-label fw-semibold">
                        Descripción <span class="text-danger">*</span>
                    </label>
                    <textarea name="descripcion" class="form-control" rows="4"
                              placeholder="Describe el producto: estado, motivo de venta, medidas, defectos…"
                              required><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- CATEGORÍA -->
        <div class="card mb-3">
            <div class="card-header fw-bold">🏷️ Categoría</div>
            <div class="card-body">
                <label class="form-label fw-semibold">Categoría</label>
                <select name="categoria_id" class="form-select">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo (($_POST['categoria_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo $cat['icono'] . ' ' . htmlspecialchars($cat['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- ESTADO -->
        <div class="card mb-3">
            <div class="card-header fw-bold">⭐ Estado del producto</div>
            <div class="card-body">
                <label class="form-label fw-semibold">
                    Estado <span class="text-danger">*</span>
                </label>
                <select name="estado_producto" class="form-select" required>
                    <option value="">Selecciona el estado…</option>
                    <?php
                    // Los valores coinciden con el ENUM de la tabla productos
                    $estados = [
                        'nuevo'      => '✨ Nuevo – Sin usar, con etiqueta original',
                        'como_nuevo' => '🌟 Como nuevo – Usado muy poco, sin desgaste',
                        'bueno'      => '👍 Bueno – Señales de uso normales, funciona bien',
                        'aceptable'  => '🔧 Aceptable – Desgaste visible, sigue funcionando',
                    ];
                    foreach ($estados as $valor => $etiqueta):
                    ?>
                        <option value="<?php echo $valor; ?>"
                            <?php echo (($_POST['estado_producto'] ?? '') === $valor) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($etiqueta); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- PRECIO -->
        <div class="card mb-3">
            <div class="card-header fw-bold">💶 Precio</div>
            <div class="card-body">
                <label class="form-label fw-semibold">
                    Precio <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input type="number" name="precio" class="form-control"
                           min="0" step="0.01" placeholder="0.00"
                           value="<?php echo htmlspecialchars($_POST['precio'] ?? ''); ?>"
                           required>
                    <span class="input-group-text">€</span>
                </div>
                <div class="form-text">Precio 0 = artículo gratis.</div>
            </div>
        </div>

        <!-- UBICACIÓN -->
        <div class="card mb-4">
            <div class="card-header fw-bold">📍 Ubicación</div>
            <div class="card-body">
                <label class="form-label fw-semibold">
                    Ciudad <span class="text-danger">*</span>
                </label>
                <input type="text" name="ubicacion" class="form-control"
                       placeholder="Ej: Madrid, Barcelona…"
                       value="<?php echo htmlspecialchars($_POST['ubicacion'] ?? ''); ?>"
                       required>
                <div class="form-text">Solo se mostrará la ciudad, nunca tu dirección exacta.</div>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-success flex-fill">🚀 Publicar anuncio</button>
            <a href="perfil.php" class="btn btn-outline-secondary">Cancelar</a>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
