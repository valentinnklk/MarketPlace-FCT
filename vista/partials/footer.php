<?php
/* Partial: footer corporativo con enlaces legales.
   Detecta automáticamente si hay sesión iniciada comprobando las claves de
   sesión que el proyecto usa actualmente (`usuario_id` + `usuario_nombre`),
   y mantiene compatibilidad con vistas que ya definan $nombreUsuario. */
if (!isset($nombreUsuario)) {
    if (isset($_SESSION['usuario_nombre'])) {
        $nombreUsuario = $_SESSION['usuario_nombre'];
    } elseif (isset($_SESSION['nombre'])) {
        $nombreUsuario = $_SESSION['nombre'];
    } else {
        $nombreUsuario = null;
    }
}
$_haySesion = !empty($nombreUsuario) || !empty($_SESSION['usuario_id']);
?>
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="footer-logo">Marketplace</span>
                <p class="footer-tagline">Conectamos a quien ofrece con quien necesita.</p>
            </div>

            <nav class="footer-nav" aria-label="Enlaces legales">
                <h5>Información legal</h5>
                <ul>
                    <li><a href="avisoLegal.php"><i class="bi bi-shield-fill-check" aria-hidden="true"></i> Aviso legal</a></li>
                    <li><a href="condicionesUso.php"><i class="bi bi-file-earmark-text-fill" aria-hidden="true"></i> Condiciones de uso</a></li>
                    <li><a href="politicaPrivacidad.php"><i class="bi bi-lock-fill" aria-hidden="true"></i> Política de privacidad</a></li>
                    <li><a href="politicaCookies.php"><i class="bi bi-cookie" aria-hidden="true"></i> Política de cookies</a></li>
                </ul>
            </nav>

            <nav class="footer-nav" aria-label="Enlaces de la plataforma">
                <h5>Plataforma</h5>
                <ul>
                    <li><a href="home.php"><i class="bi bi-house-door-fill" aria-hidden="true"></i> Inicio</a></li>
                    <?php if ($_haySesion): ?>
                        <li><a href="perfilVista.php"><i class="bi bi-person-fill" aria-hidden="true"></i> Mi perfil</a></li>
                        <li><a href="subirServicioVista.php"><i class="bi bi-plus-circle-fill" aria-hidden="true"></i> Ofrecer servicio</a></li>
                    <?php else: ?>
                        <li><a href="loginVista.php"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Iniciar sesión</a></li>
                        <li><a href="registroVista.php"><i class="bi bi-person-plus-fill" aria-hidden="true"></i> Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="footer-meta">
                <h5>Aviso</h5>
                <p class="footer-aviso-cookies">
                    <i class="bi bi-cookie" aria-hidden="true"></i>
                    Solo se utilizan cookies estrictamente necesarias para el funcionamiento del sitio.
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Marketplace. Todos los derechos reservados.</p>
            <p class="footer-disclaimer">Proyecto desarrollado con fines académicos.</p>
        </div>
    </div>
</footer>
