<?php
// vista/subirServicioVista.php
// Variables del SubirServicioController:
// $categorias (array), $errores (array)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Ofrecer servicio – Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body class="bg-light">
<a class="skip-link" href="#contenido">Saltar al contenido principal</a>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation" aria-label="Principal">
    <div id="contenido" role="main" class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
        <div class="d-flex gap-2">
            <a href="home.php"   class="btn btn-outline-light btn-sm">Inicio</a>
            <a href="perfil.php" class="btn btn-outline-light btn-sm"><i class="bi bi-person-fill" aria-hidden="true"></i> Mi perfil</a>
        </div>
    </div>
</nav>

<div class="container mt-4" style="max-width: 680px;">

    <h4 class="mb-4">Publicar servicio</h4>

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

    <form method="POST" action="subirServicio.php?accion=guardar">

        <!-- DESCRIPCIÓN -->
        <div class="card mb-3">
            <div class="card-header fw-bold"><i class="bi bi-pencil-fill" aria-hidden="true"></i> Descripción</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Título <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="titulo" class="form-control"
                           maxlength="200"
                           placeholder="Ej: Clases de matemáticas para ESO y Bachillerato"
                           value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>"
                           required>
                </div>
                <div>
                    <label class="form-label fw-semibold">
                        Descripción <span class="text-danger">*</span>
                    </label>
                    <textarea name="descripcion" class="form-control" rows="4"
                              placeholder="Describe el servicio: experiencia, metodología, qué incluye…"
                              required><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- CATEGORÍA -->
        <div class="card mb-3">
            <div class="card-header fw-bold"><i class="bi bi-tag-fill" aria-hidden="true"></i> Categoría</div>
            <div class="card-body">
                <label class="form-label fw-semibold">
                    Categoría <span class="text-danger">*</span>
                </label>
                <select name="categoria_id" class="form-select" required>
                    <option value="">Selecciona una categoría…</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo (($_POST['categoria_id'] ?? '') == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- PRECIO Y UNIDAD DE COBRO -->
        <div class="card mb-3">
            <div class="card-header fw-bold"><i class="bi bi-currency-euro" aria-hidden="true"></i> Precio</div>
            <div class="card-body row g-3">
                <div class="col-8">
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
                </div>
                <div class="col-4">
                    <label class="form-label fw-semibold">
                        Por <span class="text-danger">*</span>
                    </label>
                    <select name="unidad_cobro" class="form-select" required>
                        <option value="">…</option>
                        <?php
                        // Valores del ENUM unidad_cobro en la tabla servicios
                        $unidades = [
                            'hora'     => 'Hora',
                            'dia'      => 'Día',
                            'sesion'   => 'Sesión',
                            'trabajo'  => 'Trabajo',
                            'proyecto' => 'Proyecto',
                        ];
                        foreach ($unidades as $val => $etiqueta):
                        ?>
                            <option value="<?php echo $val; ?>"
                                <?php echo (($_POST['unidad_cobro'] ?? '') === $val) ? 'selected' : ''; ?>>
                                <?php echo $etiqueta; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- DURACIÓN ESTIMADA -->
        <div class="card mb-3">
            <div class="card-header fw-bold">⏱️ Duración estimada</div>
            <div class="card-body">
                <label class="form-label fw-semibold">Duración (opcional)</label>
                <input type="text" name="duracion_estimada" class="form-control"
                       maxlength="50"
                       placeholder="Ej: 1 hora, 2-3 horas, 1 día…"
                       value="<?php echo htmlspecialchars($_POST['duracion_estimada'] ?? ''); ?>">
            </div>
        </div>

        <!-- UBICACIÓN -->
        <div class="card mb-4">
            <div class="card-header fw-bold"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> Ubicación</div>
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
            <button type="submit" class="btn btn-success flex-fill"><i class="bi bi-rocket-takeoff-fill" aria-hidden="true"></i> Publicar servicio</button>
            <a href="perfil.php" class="btn btn-outline-secondary">Cancelar</a>
        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
</body>
</html>
