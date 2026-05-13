<?php
session_start();
$nombreUsuario = $_SESSION['nombre'] ?? null;
$rolUsuario = $_SESSION['rol'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Cookies · Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/estilo.css">
</head>
<body class="bg-light">
<a class="skip-link" href="#contenido">Saltar al contenido principal</a>

<?php include 'partials/navbar.php'; ?>

<div id="contenido" role="main" class="container my-5">
    <h1 class="text-center">Política de Cookies</h1>

    <div class="legal-doc">

        <p class="legal-meta"><strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?></p>

        <p>La presente Política de Cookies ha sido elaborada conforme a lo dispuesto en el artículo 22.2 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE), y en la Guía sobre el uso de las cookies publicada por la Agencia Española de Protección de Datos (AEPD) en su versión vigente, así como en las directrices del Comité Europeo de Protección de Datos (CEPD).</p>

        <h2>1. ¿Qué son las cookies?</h2>
        <p>Las cookies son pequeños archivos de texto que los sitios web envían al navegador del usuario y se almacenan en su terminal (ordenador, tableta o teléfono móvil). Permiten al sitio web recordar información sobre la visita, como las preferencias del usuario y otras opciones, con el fin de facilitar la navegación y ofrecer una mejor experiencia de uso.</p>

        <h2>2. Tipos de cookies utilizadas en este sitio web</h2>
        <p>El presente sitio web utiliza <strong>exclusivamente cookies técnicas o estrictamente necesarias</strong> para su funcionamiento. No se utilizan cookies analíticas, publicitarias, de personalización o cookies de terceros que requieran consentimiento previo del usuario conforme al artículo 22.2 LSSI-CE.</p>

        <div class="legal-callout legal-callout-info">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <strong>Excepción del consentimiento</strong>
                <p>Las cookies estrictamente necesarias para la prestación del servicio expresamente solicitado por el usuario están exceptuadas del deber de obtención del consentimiento, conforme al artículo 22.2 LSSI-CE. No obstante, este sitio web pone a disposición del usuario un sistema de gestión de preferencias para que pueda configurar el resto de cookies en caso de que en el futuro se incorporen.</p>
            </div>
        </div>

        <h2>3. Detalle de las cookies utilizadas</h2>
        <div class="table-responsive">
            <table class="table table-bordered legal-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Propósito</th>
                        <th>Tipo</th>
                        <th>Duración</th>
                        <th>Titular</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>PHPSESSID</code></td>
                        <td>Identifica la sesión del usuario durante su navegación e inicio de sesión.</td>
                        <td>Técnica</td>
                        <td>Sesión</td>
                        <td>Propia</td>
                    </tr>
                    <tr>
                        <td><code>cookies_consent</code></td>
                        <td>Almacena las preferencias del usuario respecto del banner de cookies.</td>
                        <td>Técnica</td>
                        <td>6 meses</td>
                        <td>Propia</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2>4. ¿Cómo gestionar las cookies?</h2>
        <p>El usuario puede gestionar las cookies a través de dos vías:</p>

        <h3>4.1. Mediante el panel de configuración del sitio web</h3>
        <p>El usuario puede acceder en cualquier momento al panel de configuración de cookies del sitio web haciendo clic en el botón inferior. Desde dicho panel podrá aceptar todas las cookies, rechazar las no necesarias o configurar individualmente cada categoría.</p>
        <p>
            <button type="button" class="btn btn-primary" onclick="if(window.MarketplaceCookies){window.MarketplaceCookies.show();}">
                <i class="bi bi-gear-fill" aria-hidden="true"></i> Abrir configuración de cookies
            </button>
        </p>

        <h3>4.2. Mediante la configuración del navegador</h3>
        <p>El usuario puede configurar su navegador para aceptar, rechazar, suprimir o ser advertido sobre la instalación de cookies. La forma de hacerlo varía en función del navegador utilizado. A continuación se ofrecen los enlaces a las páginas oficiales de los principales navegadores:</p>
        <ul>
            <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener noreferrer">Google Chrome</a></li>
            <li><a href="https://support.mozilla.org/es/kb/proteccion-antirrastreo-mejorada-firefox-escritorio" target="_blank" rel="noopener noreferrer">Mozilla Firefox</a></li>
            <li><a href="https://support.microsoft.com/es-es/microsoft-edge/eliminar-las-cookies-en-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener noreferrer">Microsoft Edge</a></li>
            <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" rel="noopener noreferrer">Safari</a></li>
            <li><a href="https://help.opera.com/en/latest/web-preferences/" target="_blank" rel="noopener noreferrer">Opera</a></li>
        </ul>

        <h2>5. Consecuencias del rechazo</h2>
        <p>Dado que las cookies utilizadas son <strong>estrictamente necesarias</strong> para el funcionamiento del sitio web, su rechazo podría impedir o dificultar el correcto funcionamiento de determinadas funcionalidades, como el mantenimiento del inicio de sesión o la gestión del perfil del usuario.</p>

        <h2>6. Modificaciones</h2>
        <p>El Titular podrá modificar la presente Política de Cookies en función de las exigencias legislativas, reglamentarias o con la finalidad de adaptar la política a las instrucciones dictadas por la Agencia Española de Protección de Datos. Cualquier cambio será notificado a los usuarios a través de la propia plataforma.</p>

    </div>

</div>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
