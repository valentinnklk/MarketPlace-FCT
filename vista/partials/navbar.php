<?php /* Partial: navbar superior común a las páginas legales */ ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation" aria-label="Principal">
    <div class="container d-flex align-items-center">
        <a class="navbar-brand" href="home.php">Marketplace</a>
        <div class="d-flex ms-auto align-items-center" style="gap: .4rem;">
            <?php if (!empty($nombreUsuario)): ?>
                <a href="home.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-house-door-fill" aria-hidden="true"></i> Inicio
                </a>
                <a href="perfilVista.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-person-fill" aria-hidden="true"></i> <?php echo htmlspecialchars($nombreUsuario); ?>
                </a>
            <?php else: ?>
                <a href="loginVista.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Iniciar sesión
                </a>
                <a href="registroVista.php" class="btn btn-success btn-sm">
                    <i class="bi bi-person-plus-fill" aria-hidden="true"></i> Registrarse
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
