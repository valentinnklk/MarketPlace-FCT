/**
 * cookies.js — Aviso simplificado de cookies estrictamente necesarias.
 *
 * Comportamiento:
 *  - Al cargar la página: si no se ha guardado la confirmación, muestra el banner.
 *  - Botón "Entendido" → guarda confirmación en localStorage durante 6 meses.
 *
 * Como solo se usan cookies técnicas, no hay configuración granular.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'marketplace_cookies_aviso';
    var STORAGE_TS  = 'marketplace_cookies_ts';
    var SIX_MONTHS_MS = 1000 * 60 * 60 * 24 * 180;

    function yaConfirmado() {
        try {
            var v  = localStorage.getItem(STORAGE_KEY);
            var ts = parseInt(localStorage.getItem(STORAGE_TS), 10);
            if (!v || !ts) return false;
            if (Date.now() - ts > SIX_MONTHS_MS) {
                localStorage.removeItem(STORAGE_KEY);
                localStorage.removeItem(STORAGE_TS);
                return false;
            }
            return true;
        } catch (e) {
            return false;
        }
    }

    function guardarConfirmacion() {
        try {
            localStorage.setItem(STORAGE_KEY, '1');
            localStorage.setItem(STORAGE_TS, String(Date.now()));
        } catch (e) { /* silencio */ }
    }

    function ocultarBanner() {
        var banner = document.getElementById('cookies-banner');
        if (banner) {
            banner.classList.add('cookies-banner-hidden');
            setTimeout(function () { banner.hidden = true; }, 350);
        }
    }

    function mostrarBanner() {
        var banner = document.getElementById('cookies-banner');
        if (!banner) return;
        banner.hidden = false;
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                banner.classList.remove('cookies-banner-hidden');
            });
        });
    }

    function init() {
        if (yaConfirmado()) return;
        setTimeout(mostrarBanner, 600);
        var btn = document.getElementById('cookies-btn-accept');
        if (btn) {
            btn.addEventListener('click', function () {
                guardarConfirmacion();
                ocultarBanner();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
