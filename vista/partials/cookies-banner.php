<?php /* Partial: banner de cookies + modal de configuración */ ?>
<!-- Banner inferior de cookies -->
<div id="cookies-banner" class="cookies-banner" role="dialog" aria-modal="false" aria-labelledby="cookies-banner-title" aria-describedby="cookies-banner-desc" hidden>
    <div class="cookies-banner-inner">
        <div class="cookies-banner-icon" aria-hidden="true">
            <i class="bi bi-cookie"></i>
        </div>
        <div class="cookies-banner-content">
            <h2 id="cookies-banner-title" class="cookies-banner-heading">Su privacidad es importante</h2>
            <p id="cookies-banner-desc">
                Utilizamos cookies estrictamente necesarias para el funcionamiento de la plataforma. Puede consultar el detalle en nuestra <a href="politicaCookies.php">Política de Cookies</a> o configurar sus preferencias.
            </p>
        </div>
        <div class="cookies-banner-actions">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="cookies-btn-config">
                <i class="bi bi-sliders" aria-hidden="true"></i> Configurar
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="cookies-btn-reject">
                Rechazar
            </button>
            <button type="button" class="btn btn-primary btn-sm" id="cookies-btn-accept">
                <i class="bi bi-check-lg" aria-hidden="true"></i> Aceptar todas
            </button>
        </div>
    </div>
</div>

<!-- Modal de configuración granular de cookies -->
<div class="modal fade" id="cookies-modal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="cookies-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="cookies-modal-title">
                    <i class="bi bi-gear-fill" aria-hidden="true"></i> Configuración de cookies
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="cookies-modal-intro">Esta plataforma utiliza diferentes tipos de cookies para funcionar y mejorar la experiencia del usuario. A continuación puede activar o desactivar cada categoría según sus preferencias.</p>

                <!-- Categoría 1: técnicas (obligatorias) -->
                <div class="cookies-cat">
                    <div class="cookies-cat-head">
                        <div>
                            <strong>Cookies estrictamente necesarias</strong>
                            <span class="cookies-cat-badge cookies-cat-required">Siempre activas</span>
                        </div>
                        <label class="cookies-switch" aria-label="Cookies necesarias (siempre activas)">
                            <input type="checkbox" checked disabled aria-disabled="true">
                            <span class="cookies-slider"></span>
                        </label>
                    </div>
                    <p class="cookies-cat-desc">Imprescindibles para el funcionamiento de la plataforma. Permiten mantener la sesión iniciada, recordar las preferencias de configuración de cookies y proteger la navegación frente a accesos no autorizados. No pueden desactivarse.</p>
                </div>

                <!-- Categoría 2: analíticas (no usadas actualmente) -->
                <div class="cookies-cat cookies-cat-inactive">
                    <div class="cookies-cat-head">
                        <div>
                            <strong>Cookies analíticas</strong>
                            <span class="cookies-cat-badge cookies-cat-disabled">No utilizadas</span>
                        </div>
                        <label class="cookies-switch" aria-label="Cookies analíticas (no disponibles)">
                            <input type="checkbox" id="cookies-analytics" disabled aria-disabled="true">
                            <span class="cookies-slider"></span>
                        </label>
                    </div>
                    <p class="cookies-cat-desc">Permitirían medir el uso de la plataforma de forma anónima para mejorar el servicio (por ejemplo, páginas más visitadas o tiempo medio de navegación). <strong>Actualmente no se utilizan en este sitio web.</strong></p>
                </div>

                <!-- Categoría 3: marketing (no usadas actualmente) -->
                <div class="cookies-cat cookies-cat-inactive">
                    <div class="cookies-cat-head">
                        <div>
                            <strong>Cookies de personalización y marketing</strong>
                            <span class="cookies-cat-badge cookies-cat-disabled">No utilizadas</span>
                        </div>
                        <label class="cookies-switch" aria-label="Cookies de marketing (no disponibles)">
                            <input type="checkbox" id="cookies-marketing" disabled aria-disabled="true">
                            <span class="cookies-slider"></span>
                        </label>
                    </div>
                    <p class="cookies-cat-desc">Servirían para mostrar contenido y publicidad personalizada en función del comportamiento de navegación. <strong>Actualmente no se utilizan en este sitio web.</strong></p>
                </div>

                <p class="cookies-modal-foot"><i class="bi bi-info-circle" aria-hidden="true"></i> Puede modificar sus preferencias en cualquier momento desde el enlace "Configurar cookies" del pie de página.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="cookies-modal-save">
                    <i class="bi bi-check-lg" aria-hidden="true"></i> Guardar preferencias
                </button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/cookies.js"></script>
