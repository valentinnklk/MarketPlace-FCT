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
    <title>Condiciones de Uso · Marketplace</title>
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
    <h1 class="text-center">Condiciones de Uso</h1>

    <div class="legal-doc">

        <p class="legal-meta"><strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?></p>

        <p>Las presentes Condiciones de Uso (en adelante, "las Condiciones") regulan el acceso y la utilización del sitio web Marketplace (en adelante, "la plataforma"), titularidad de [NOMBRE DEL TITULAR], con NIF/DNI [NIF O DNI], domiciliado en [DIRECCIÓN COMPLETA], correo electrónico de contacto [EMAIL@DOMINIO.COM] (en adelante, "el Titular").</p>

        <h2>1. Objeto</h2>
        <p>La plataforma constituye un servicio de la sociedad de la información cuyo objeto es poner en contacto a personas que ofrecen servicios profesionales o particulares (en adelante, "prestadores") con personas interesadas en contratarlos (en adelante, "clientes"), actuando exclusivamente como intermediario tecnológico.</p>
        <p>El Titular <strong>no presta los servicios ofertados</strong>, no es parte en las relaciones contractuales que puedan establecerse entre prestadores y clientes, y no participa en los pagos, cobros o ejecución de los servicios. Toda relación derivada de la contratación de un servicio se entenderá establecida directa y exclusivamente entre el prestador y el cliente.</p>

        <h2>2. Aceptación de las Condiciones</h2>
        <p>La utilización de la plataforma atribuye al usuario la condición de tal y supone la aceptación plena y sin reservas de las presentes Condiciones, así como de la Política de Privacidad, la Política de Cookies y el Aviso Legal. En consecuencia, antes de utilizar la plataforma, el usuario debe leer atentamente todos estos documentos.</p>
        <p>En el momento del registro, el usuario deberá marcar la casilla de aceptación expresa de las presentes Condiciones. Sin dicha aceptación no será posible completar el alta en la plataforma.</p>

        <h2>3. Capacidad y edad mínima</h2>
        <p>Para registrarse y utilizar la plataforma, el usuario declara y garantiza que:</p>
        <ul>
            <li>Es mayor de 18 años.</li>
            <li>Tiene plena capacidad jurídica de obrar.</li>
            <li>Actúa por sí mismo y bajo su exclusiva responsabilidad.</li>
            <li>Los datos facilitados son veraces, exactos y completos.</li>
        </ul>

        <h2>4. Registro de usuario</h2>
        <p>El registro en la plataforma se realiza a través del formulario habilitado al efecto. Para completar el registro, el usuario deberá facilitar los datos solicitados con carácter obligatorio:</p>
        <ul>
            <li>Para usuarios cliente: nombre, apellidos, correo electrónico y contraseña.</li>
            <li>Para usuarios prestador: adicionalmente, número de Documento Nacional de Identidad (DNI) a efectos de verificación de identidad.</li>
        </ul>
        <p>El usuario es el único responsable de la veracidad de los datos facilitados, así como de mantener la confidencialidad de su contraseña. El Titular no se responsabiliza del uso indebido que terceros pudieran realizar de las credenciales del usuario.</p>

        <h2>5. Obligaciones del usuario prestador</h2>
        <p>El usuario que oferte servicios a través de la plataforma se compromete a:</p>
        <ol>
            <li>Cumplir con la normativa legal, fiscal, administrativa y sectorial que resulte de aplicación a su actividad profesional o particular.</li>
            <li>Disponer, en su caso, de las autorizaciones, licencias y titulaciones exigibles para la prestación del servicio anunciado.</li>
            <li>Cumplir las obligaciones fiscales y de Seguridad Social derivadas de su actividad.</li>
            <li>Publicar información veraz, exacta y completa sobre los servicios ofertados, incluyendo precio, ámbito geográfico y condiciones de prestación.</li>
            <li>Atender con diligencia las solicitudes de los clientes y prestar el servicio conforme a lo acordado.</li>
            <li>No utilizar la plataforma para fines distintos a los aquí establecidos.</li>
        </ol>

        <h2>6. Obligaciones del usuario cliente</h2>
        <p>El usuario que utilice la plataforma para contratar servicios se compromete a:</p>
        <ol>
            <li>Facilitar al prestador la información necesaria para la correcta prestación del servicio.</li>
            <li>Abonar el precio acordado al prestador en los términos pactados.</li>
            <li>Emitir valoraciones veraces, basadas en la experiencia real de contratación.</li>
            <li>No utilizar la plataforma con finalidad fraudulenta o contraria a la buena fe.</li>
        </ol>

        <h2>7. Conducta del usuario</h2>
        <p>El usuario se compromete a hacer un uso adecuado de la plataforma y, en particular, se compromete a no emplearla para:</p>
        <ul>
            <li>Realizar actividades ilícitas, ilegales o contrarias a la buena fe y al orden público.</li>
            <li>Difundir contenidos o propaganda de carácter racista, xenófobo, pornográfico, ilegal o que atente contra los derechos humanos.</li>
            <li>Provocar daños en los sistemas físicos y lógicos del Titular, de sus proveedores o de terceros.</li>
            <li>Introducir o difundir virus informáticos o cualesquiera otros sistemas físicos o lógicos que sean susceptibles de provocar daños.</li>
            <li>Intentar acceder y, en su caso, utilizar las cuentas de correo electrónico o las áreas restringidas de otros usuarios.</li>
            <li>Vulnerar derechos de propiedad intelectual o industrial de terceros.</li>
            <li>Suplantar la identidad de otra persona, real o ficticia.</li>
            <li>Recopilar datos personales de otros usuarios sin su consentimiento.</li>
        </ul>

        <h2>8. Sistema de reseñas y valoraciones</h2>
        <p>La plataforma permite a los usuarios que hayan contratado un servicio emitir una valoración y una reseña sobre el prestador. Las valoraciones deben ser veraces, basadas en hechos reales, respetuosas y no contener insultos, amenazas, expresiones ofensivas o información personal de terceros.</p>
        <p>El Titular se reserva el derecho a retirar, sin previo aviso, aquellas reseñas que considere contrarias a las presentes Condiciones o a la legislación vigente.</p>

        <h2>9. Responsabilidad del Titular como intermediario</h2>
        <p>De conformidad con lo dispuesto en los artículos 14 a 17 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y de Comercio Electrónico, el Titular actúa como intermediario, sin que pueda imputársele responsabilidad por los contenidos publicados por los usuarios, salvo en los supuestos en los que, teniendo conocimiento efectivo de la ilicitud de un contenido, no actúe con diligencia para retirarlo o impedir el acceso al mismo.</p>
        <p>El Titular no garantiza:</p>
        <ul>
            <li>La calidad, idoneidad, legalidad o fiabilidad de los servicios ofertados por los prestadores.</li>
            <li>La solvencia, identidad real o cualificación profesional de los prestadores, sin perjuicio de la verificación del número de DNI facilitado.</li>
            <li>La disponibilidad ininterrumpida de la plataforma, que podrá suspenderse temporalmente por motivos de mantenimiento, actualización o causas de fuerza mayor.</li>
        </ul>

        <h2>10. Notificación de contenidos ilícitos</h2>
        <p>Cualquier usuario que tenga conocimiento de la existencia de contenidos ilícitos, lesivos o contrarios a las presentes Condiciones podrá notificarlo al Titular a través del correo electrónico de contacto, aportando los datos que permitan identificar el contenido denunciado y los motivos de la denuncia. La plataforma incorpora un sistema de "reportes" para facilitar esta notificación.</p>

        <h2>11. Suspensión y cancelación de la cuenta</h2>
        <p>El Titular se reserva el derecho a suspender o cancelar, sin previo aviso, la cuenta de aquellos usuarios que incumplan las presentes Condiciones, manifiestamente o de forma reiterada. La cancelación de la cuenta no eximirá al usuario de las responsabilidades en que hubiera podido incurrir frente al Titular o frente a otros usuarios.</p>
        <p>El usuario podrá, en cualquier momento, solicitar la baja voluntaria de su cuenta mediante el panel de configuración de su perfil o mediante solicitud al correo electrónico de contacto.</p>

        <h2>12. Propiedad intelectual</h2>
        <p>Los contenidos publicados por los usuarios (descripciones de servicios, imágenes, valoraciones) seguirán siendo de su titularidad. No obstante, el usuario otorga al Titular una licencia no exclusiva, gratuita y con ámbito territorial mundial, para reproducir, distribuir, comunicar públicamente y transformar dichos contenidos en la medida estrictamente necesaria para la prestación del servicio de intermediación.</p>

        <h2>13. Modificación de las Condiciones</h2>
        <p>El Titular podrá modificar las presentes Condiciones en cualquier momento, comunicándolo a los usuarios a través de la plataforma con una antelación mínima de 15 días. La continuidad en el uso de la plataforma tras la entrada en vigor de las modificaciones implicará su aceptación.</p>

        <h2>14. Ley aplicable y jurisdicción</h2>
        <p>Las presentes Condiciones se regirán por la legislación española. Para la resolución de cualquier controversia, las partes se someten a los Juzgados y Tribunales del domicilio del usuario consumidor, conforme al artículo 90.2 del Real Decreto Legislativo 1/2007, de 16 de noviembre, por el que se aprueba el texto refundido de la Ley General para la Defensa de los Consumidores y Usuarios.</p>
        <p>De conformidad con el Reglamento (UE) 524/2013 sobre resolución de litigios en línea, se informa al usuario consumidor de la existencia de la plataforma europea de resolución de litigios en línea, accesible a través del enlace <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener noreferrer">https://ec.europa.eu/consumers/odr</a>.</p>

    </div>

</div>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
