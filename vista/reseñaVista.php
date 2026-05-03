<?php
// vista/reseñaVista.php
session_start();

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../controladores/reseñaControlador.php';

$ctrl = new ReseñaControlador($conexion);

// Enrutado: si es POST → crear; si es GET → mostrar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['accion'] ?? '') === 'crear') {
    $ctrl->crear();
    exit;
}

$data = $ctrl->mostrarFormulario();

$puede       = $data['puede'];
$motivo      = $data['motivo'];
$contrato    = $data['contrato'];
$contrato_id = $data['contrato_id'];
$error       = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Valorar servicio · Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .estrellas-seleccion {
            font-size: 2.2rem;
            direction: rtl;
            display: inline-flex;
            justify-content: flex-end;
        }
        .estrellas-seleccion input { display: none; }
        .estrellas-seleccion label {
            color: #dee2e6;
            cursor: pointer;
            padding: 0 4px;
            transition: color .15s;
        }
        .estrellas-seleccion label:hover,
        .estrellas-seleccion label:hover ~ label,
        .estrellas-seleccion input:checked ~ label,
        .estrellas-seleccion input:checked ~ label ~ label {
            color: #f5c518;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="home.php">Marketplace</a>
        <a class="btn btn-outline-light btn-sm" href="perfil.php?tab=contratados">← Volver a mis contratos</a>
    </div>
</nav>

<div class="container mt-4" style="max-width:640px;">

    <?php if (!$contrato): ?>
        <div class="alert alert-warning">El contrato solicitado no existe.</div>

    <?php elseif (!$puede): ?>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">No se puede valorar este servicio</h4>
                <p class="text-muted"><?= htmlspecialchars($motivo) ?></p>

                <?php if (!empty($contrato['reseña_id'])): ?>
                    <hr>
                    <h6>Tu valoración ya registrada:</h6>
                    <div class="mb-2" style="color:#f5c518; font-size:1.4rem;">
                        <?= str_repeat('★', (int) $contrato['puntuacion'])
                           . str_repeat('☆', 5 - (int) $contrato['puntuacion']) ?>
                    </div>
                    <?php if (!empty($contrato['comentario'])): ?>
                        <p class="mb-1"><?= nl2br(htmlspecialchars($contrato['comentario'])) ?></p>
                    <?php endif; ?>
                    <small class="text-muted">
                        Publicada el <?= date('d/m/Y H:i', strtotime($contrato['fecha_reseña'])) ?>
                    </small>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="perfil.php?tab=contratados" class="btn btn-outline-secondary btn-sm">Volver a mis contratos</a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <?php if ($error === 'datos'): ?>
            <div class="alert alert-danger">Debes seleccionar una puntuación entre 1 y 5 estrellas.</div>
        <?php elseif ($error === 'no_permitido'): ?>
            <div class="alert alert-danger">No ha sido posible registrar la valoración.</div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Valorar servicio</h4>
                <p class="text-muted mb-3">
                    <b><?= htmlspecialchars($contrato['titulo']) ?></b>
                    · Prestador: <?= htmlspecialchars($contrato['prestador_nombre']) ?>
                    · Contrato #<?= (int) $contrato['id'] ?>
                </p>

                <form method="POST" action="reseñaVista.php?accion=crear">
                    <input type="hidden" name="contrato_id" value="<?= (int) $contrato_id ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Puntuación</label>
                        <div class="estrellas-seleccion">
                            <!-- rtl invierte el orden visual: ponemos del 5 al 1 -->
                            <input type="radio" name="puntuacion" id="p5" value="5"><label for="p5">★</label>
                            <input type="radio" name="puntuacion" id="p4" value="4"><label for="p4">★</label>
                            <input type="radio" name="puntuacion" id="p3" value="3"><label for="p3">★</label>
                            <input type="radio" name="puntuacion" id="p2" value="2"><label for="p2">★</label>
                            <input type="radio" name="puntuacion" id="p1" value="1"><label for="p1">★</label>
                        </div>
                        <small class="text-muted d-block">Selecciona entre 1 y 5 estrellas.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Comentario (opcional)</label>
                        <textarea name="comentario" rows="4" class="form-control"
                                  maxlength="1000"
                                  placeholder="Cuenta a otros usuarios cómo ha sido tu experiencia con este servicio…"></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Publicar valoración</button>
                        <a href="perfil.php?tab=contratados" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
