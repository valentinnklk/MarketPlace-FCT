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
    <title>Política de Privacidad · Marketplace</title>
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
    <h1 class="text-center">Política de Privacidad</h1>

    <div class="legal-doc">

        <p class="legal-meta"><strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?></p>

        <p>El titular del sitio web Marketplace (en adelante, "el Responsable del Tratamiento") considera de extrema importancia la privacidad de los usuarios y el cumplimiento de la normativa aplicable en materia de protección de datos de carácter personal. La presente Política de Privacidad se ha elaborado conforme a lo establecido en el <strong>Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de estos datos (RGPD)</strong>, y en la <strong>Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personales y garantía de los derechos digitales (LOPDGDD)</strong>.</p>

        <h2>1. Responsable del tratamiento</h2>
        <ul>
            <li><strong>Identidad:</strong> [NOMBRE COMPLETO DEL TITULAR]</li>
            <li><strong>NIF/DNI:</strong> [NIF O DNI DEL TITULAR]</li>
            <li><strong>Domicilio:</strong> [DIRECCIÓN COMPLETA]</li>
            <li><strong>Correo electrónico:</strong> [EMAIL@DOMINIO.COM]</li>
        </ul>

        <h2>2. Datos personales objeto de tratamiento</h2>
        <p>A través de los formularios y procesos del sitio web se recogen los siguientes datos personales:</p>

        <h3>2.1. Datos del registro de usuario</h3>
        <ul>
            <li>Nombre y apellidos</li>
            <li>Dirección de correo electrónico</li>
            <li>Contraseña (almacenada cifrada mediante algoritmo bcrypt)</li>
            <li>Imagen de perfil (opcional)</li>
        </ul>

        <h3>2.2. Datos del prestador de servicios (DNI)</h3>
        <p>Las personas usuarias que deseen ofrecer servicios a través de la plataforma deberán facilitar adicionalmente su número de <strong>Documento Nacional de Identidad (DNI)</strong> o documento equivalente.</p>
        <div class="legal-callout legal-callout-warning">
            <i class="bi bi-shield-lock-fill"></i>
            <div>
                <strong>Tratamiento del DNI</strong>
                <p>El DNI constituye un dato identificativo cuyo tratamiento requiere especiales garantías. En cumplimiento del principio de minimización del artículo 5.1.c del RGPD, únicamente se solicita el <em>número</em> del DNI, sin requerir copia ni imagen del documento. El número se almacena cifrado en la base de datos y el acceso al mismo queda restringido a personal autorizado mediante sistema de control de accesos.</p>
            </div>
        </div>

        <h3>2.3. Datos derivados del uso de la plataforma</h3>
        <ul>
            <li>Servicios publicados (título, descripción, categoría, precio, valoraciones)</li>
            <li>Mensajes intercambiados con otros usuarios a través del sistema interno de mensajería</li>
            <li>Reseñas y valoraciones emitidas o recibidas</li>
            <li>Historial de contrataciones realizadas como cliente o como prestador</li>
            <li>Dirección IP, tipo de navegador y datos técnicos derivados de la sesión (almacenados temporalmente en logs)</li>
        </ul>

        <h2>3. Finalidad del tratamiento</h2>
        <p>Los datos personales recabados se tratarán para las siguientes finalidades:</p>
        <ol>
            <li><strong>Gestión del registro y acceso a la plataforma:</strong> creación y mantenimiento de la cuenta de usuario, autenticación e inicio de sesión.</li>
            <li><strong>Prestación del servicio de intermediación:</strong> publicación y exploración de servicios, comunicación entre usuarios, emisión de reseñas.</li>
            <li><strong>Verificación de identidad de los prestadores:</strong> exclusivamente respecto del número de DNI facilitado, con la finalidad de prevenir el fraude y dar mayor garantía a los usuarios que contraten servicios a través de la plataforma.</li>
            <li><strong>Gestión administrativa:</strong> atención a las consultas, solicitudes y reclamaciones que los usuarios planteen.</li>
            <li><strong>Cumplimiento de obligaciones legales:</strong> conservación de los datos durante los plazos legalmente exigibles y respuesta a requerimientos de autoridades competentes.</li>
        </ol>

        <h2>4. Base jurídica del tratamiento</h2>
        <p>Las bases jurídicas que legitiman el tratamiento de sus datos personales son las siguientes:</p>
        <ul>
            <li><strong>Artículo 6.1.b RGPD (ejecución de un contrato):</strong> para la gestión del registro, prestación del servicio de intermediación y atención al usuario.</li>
            <li><strong>Artículo 6.1.a RGPD (consentimiento):</strong> para el tratamiento del DNI con finalidad de verificación de identidad, prestado de forma libre, específica, informada e inequívoca mediante la marcación de la casilla habilitada al efecto.</li>
            <li><strong>Artículo 6.1.c RGPD (cumplimiento de una obligación legal):</strong> para la conservación de los datos durante los plazos legalmente exigibles.</li>
            <li><strong>Artículo 6.1.f RGPD (interés legítimo):</strong> para la prevención del fraude, la garantía de la seguridad de la plataforma y la mejora del servicio.</li>
        </ul>

        <h2>5. Plazo de conservación</h2>
        <p>Los datos personales serán conservados durante el tiempo necesario para cumplir con la finalidad para la que se recabaron y para determinar las posibles responsabilidades derivadas de dicha finalidad y del tratamiento de los datos. Concretamente:</p>
        <ul>
            <li><strong>Datos de la cuenta de usuario:</strong> mientras la cuenta permanezca activa. En caso de baja o eliminación voluntaria, se conservarán bloqueados durante los plazos de prescripción legalmente establecidos (hasta 6 años, conforme al artículo 30 del Código de Comercio).</li>
            <li><strong>Datos del DNI:</strong> mientras el usuario mantenga la condición de prestador activo. Tras la baja como prestador, el número se eliminará en un plazo máximo de 30 días, salvo obligación legal de conservación.</li>
            <li><strong>Mensajes y comunicaciones internas:</strong> hasta 2 años desde la última actividad, para garantizar la trazabilidad de las contrataciones.</li>
            <li><strong>Reseñas y valoraciones:</strong> permanecerán visibles mientras la cuenta del prestador esté activa, pudiendo anonimizarse a petición del usuario emisor.</li>
        </ul>

        <h2>6. Destinatarios de los datos</h2>
        <p>Los datos personales no serán cedidos ni comunicados a terceros, salvo en los siguientes supuestos:</p>
        <ul>
            <li>Por <strong>obligación legal</strong>, ante Jueces y Tribunales, Fuerzas y Cuerpos de Seguridad del Estado, Administraciones Públicas competentes y la Agencia Española de Protección de Datos.</li>
            <li>A <strong>otros usuarios de la plataforma</strong>, exclusivamente en lo relativo al nombre del prestador, su imagen de perfil, sus valoraciones y los servicios que ofrece. <strong>El DNI no se mostrará en ningún caso a otros usuarios.</strong></li>
            <li>A <strong>proveedores tecnológicos</strong> con los que se haya suscrito el correspondiente contrato de encargado de tratamiento conforme al artículo 28 del RGPD (proveedor de alojamiento web, servicio de correo electrónico).</li>
        </ul>
        <p>No se realizarán transferencias internacionales de datos a países situados fuera del Espacio Económico Europeo.</p>

        <h2>7. Derechos del usuario</h2>
        <p>El usuario podrá ejercer en cualquier momento los siguientes derechos reconocidos por el RGPD y la LOPDGDD:</p>
        <ul>
            <li><strong>Acceso:</strong> conocer qué datos personales son objeto de tratamiento.</li>
            <li><strong>Rectificación:</strong> solicitar la modificación de los datos inexactos.</li>
            <li><strong>Supresión ("derecho al olvido"):</strong> solicitar la eliminación de los datos cuando ya no sean necesarios.</li>
            <li><strong>Limitación del tratamiento:</strong> solicitar la suspensión del tratamiento en determinados supuestos.</li>
            <li><strong>Portabilidad:</strong> recibir los datos en un formato estructurado y de uso común.</li>
            <li><strong>Oposición:</strong> oponerse al tratamiento de los datos por motivos relacionados con la situación particular del usuario.</li>
            <li><strong>Retirada del consentimiento:</strong> retirar el consentimiento prestado en cualquier momento, sin que ello afecte a la licitud del tratamiento anterior a la retirada.</li>
            <li><strong>Reclamación ante la autoridad de control:</strong> presentar reclamación ante la <strong>Agencia Española de Protección de Datos (AEPD)</strong>, con domicilio en la calle Jorge Juan número 6, 28001 Madrid, o a través de su sede electrónica en <a href="https://www.aepd.es" target="_blank" rel="noopener noreferrer">www.aepd.es</a>.</li>
        </ul>
        <p>Para el ejercicio de estos derechos, el usuario podrá dirigirse al Responsable del Tratamiento mediante el envío de un correo electrónico a la dirección indicada en el apartado 1, indicando en el asunto "Ejercicio de derechos RGPD" y adjuntando documento acreditativo de su identidad.</p>

        <h2>8. Medidas de seguridad</h2>
        <p>El Responsable del Tratamiento ha adoptado las medidas técnicas y organizativas necesarias para garantizar la seguridad e integridad de los datos personales tratados, así como para evitar su pérdida, alteración o acceso no autorizado, atendiendo al estado de la tecnología, la naturaleza de los datos y los riesgos a los que están expuestos.</p>
        <p>Concretamente, se implementan las siguientes medidas:</p>
        <ul>
            <li>Conexión cifrada mediante protocolo HTTPS (TLS).</li>
            <li>Almacenamiento cifrado de contraseñas mediante algoritmo bcrypt.</li>
            <li>Almacenamiento cifrado del número de DNI en la base de datos.</li>
            <li>Sistema de control de accesos basado en roles (usuario, prestador, administrador).</li>
            <li>Mecanismos de protección frente a ataques de inyección SQL, XSS y CSRF.</li>
            <li>Copias de seguridad periódicas de la base de datos.</li>
        </ul>

        <h2>9. Menores de edad</h2>
        <p>La plataforma está dirigida exclusivamente a personas mayores de 18 años. No se recogen datos de menores de edad. En caso de detectarse el registro de una persona menor, se procederá a la eliminación inmediata de su cuenta y de los datos asociados.</p>

        <h2>10. Modificaciones de la Política de Privacidad</h2>
        <p>El Responsable del Tratamiento se reserva el derecho a modificar la presente Política de Privacidad para adaptarla a novedades legislativas o jurisprudenciales. En tales supuestos, se anunciará en el sitio web los cambios introducidos con razonable antelación a su puesta en práctica.</p>

    </div>

</div>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
