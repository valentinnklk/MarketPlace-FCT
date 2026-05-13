<?php
/* Partial: footer corporativo con enlaces legales.
   Defensivo ante vistas que no hayan iniciado sesión todavía. */
if (!isset($nombreUsuario)) {
    $nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : null;
}
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
                    <?php if (!empty($nombreUsuario)): ?>
                        <li><a href="perfilVista.php"><i class="bi bi-person-fill" aria-hidden="true"></i> Mi perfil</a></li>
                        <li><a href="subirServicioVista.php"><i class="bi bi-plus-circle-fill" aria-hidden="true"></i> Ofrecer servicio</a></li>
                    <?php else: ?>
                        <li><a href="loginVista.php"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Iniciar sesión</a></li>
                        <li><a href="registroVista.php"><i class="bi bi-person-plus-fill" aria-hidden="true"></i> Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="footer-meta">
                <h5>Configuración</h5>
                <button type="button" class="btn btn-outline-light btn-sm footer-cookies-btn" onclick="if(window.MarketplaceCookies){window.MarketplaceCookies.show();}">
                    <i class="bi bi-gear-fill" aria-hidden="true"></i> Configurar cookies
                </button>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Marketplace. Todos los derechos reservados.</p>
            <p class="footer-disclaimer">Proyecto desarrollado con fines académicos.</p>
        </div>
    </div>
</footer>
