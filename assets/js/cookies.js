/**
 * cookies.js — Gestión del banner de cookies de Marketplace
 * 
 * Comportamiento:
 *  - Al cargar la página: si no existe la cookie "cookies_consent" o
 *    "cookies_preferences" en localStorage, muestra el banner inferior.
 *  - Los botones del banner (Aceptar / Rechazar / Configurar) actualizan
 *    la preferencia y ocultan el banner.
 *  - El modal de configuración permite gestión granular por categorías.
 *  - Las preferencias se guardan en localStorage durante 6 meses.
 *  - Expone window.MarketplaceCookies.show() para reabrir el panel
 *    desde el footer o desde la Política de Cookies.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'marketplace_cookies_consent';
    var STORAGE_TS  = 'marketplace_cookies_ts';
    var SIX_MONTHS_MS = 1000 * 60 * 60 * 24 * 180;

    function readConsent() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            var ts = parseInt(localStorage.getItem(STORAGE_TS), 10);
            if (!raw || !ts) return null;
            if (Date.now() - ts > SIX_MONTHS_MS) {
                // Caducidad de 6 meses: pedir consentimiento de nuevo
                localStorage.removeItem(STORAGE_KEY);
                localStorage.removeItem(STORAGE_TS);
                return null;
            }
            return JSON.parse(raw);
        } catch (e) {
            return null;
        }
    }

    function saveConsent(consent) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(consent));
            localStorage.setItem(STORAGE_TS, String(Date.now()));
            // También guardamos cookie técnica para que el backend
            // (si quisiera) pueda saber que el usuario ya respondió
            var expires = new Date(Date.now() + SIX_MONTHS_MS).toUTCString();
            document.cookie = 'cookies_consent=' + (consent.analytics ? 'all' : 'necessary') +
                              '; expires=' + expires + '; path=/; SameSite=Lax';
        } catch (e) { /* fallar en silencio */ }
    }

    function hideBanner() {
        var banner = document.getElementById('cookies-banner');
        if (banner) {
            banner.classList.add('cookies-banner-hidden');
            setTimeout(function () { banner.hidden = true; }, 350);
        }
    }

    function showBanner() {
        var banner = document.getElementById('cookies-banner');
        if (!banner) return;
        banner.hidden = false;
        // doble RAF para que la transición CSS se aplique
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                banner.classList.remove('cookies-banner-hidden');
            });
        });
    }

    function openModal() {
        var modalEl = document.getElementById('cookies-modal');
        if (!modalEl) return;
        if (window.bootstrap && window.bootstrap.Modal) {
            var modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else {
            // Fallback si Bootstrap aún no ha cargado
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.setAttribute('aria-hidden', 'false');
        }
    }

    function acceptAll() {
        saveConsent({ necessary: true, analytics: true, marketing: true });
        hideBanner();
    }

    function rejectAll() {
        saveConsent({ necessary: true, analytics: false, marketing: false });
        hideBanner();
    }

    function saveFromModal() {
        // Las casillas de analíticas y marketing están deshabilitadas
        // (no se usan), así que solo se guardan las técnicas como activas.
        var analyticsEl = document.getElementById('cookies-analytics');
        var marketingEl = document.getElementById('cookies-marketing');
        saveConsent({
            necessary: true,
            analytics: analyticsEl ? !!analyticsEl.checked : false,
            marketing: marketingEl ? !!marketingEl.checked : false
        });
        hideBanner();
        // Cerrar modal
        var modalEl = document.getElementById('cookies-modal');
        if (modalEl && window.bootstrap && window.bootstrap.Modal) {
            var modal = window.bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }
    }

    function init() {
        // Si ya existe consentimiento válido, no mostramos nada
        var existing = readConsent();
        if (!existing) {
            // Pequeño retraso para que la animación entre suave
            setTimeout(showBanner, 600);
        }

        // Listeners de los botones del banner
        var btnAccept = document.getElementById('cookies-btn-accept');
        var btnReject = document.getElementById('cookies-btn-reject');
        var btnConfig = document.getElementById('cookies-btn-config');
        var btnSave   = document.getElementById('cookies-modal-save');

        if (btnAccept) btnAccept.addEventListener('click', acceptAll);
        if (btnReject) btnReject.addEventListener('click', rejectAll);
        if (btnConfig) btnConfig.addEventListener('click', openModal);
        if (btnSave)   btnSave.addEventListener('click', saveFromModal);
    }

    // API pública para reabrir el panel desde el footer
    window.MarketplaceCookies = {
        show: function () {
            // Si el banner está oculto pero existe consentimiento, abrimos directamente el modal
            var banner = document.getElementById('cookies-banner');
            if (banner && !banner.hidden) {
                openModal();
            } else {
                openModal();
            }
        },
        reset: function () {
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(STORAGE_TS);
            document.cookie = 'cookies_consent=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
            showBanner();
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
