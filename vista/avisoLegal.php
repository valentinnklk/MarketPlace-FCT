<?php
session_start();
$nombreUsuario = $_SESSION['usuario_nombre'] ?? $_SESSION['nombre'] ?? null;
$rolUsuario = $_SESSION['rol'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso Legal · Marketplace</title>
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
    <h1 class="text-center">Aviso Legal</h1>

    <div class="legal-doc">

        <p class="legal-meta"><strong>Última actualización:</strong> <?php echo date('d/m/Y'); ?></p>

        <h2>1. Identificación del titular</h2>
        <p>En cumplimiento de lo dispuesto en el artículo 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE), se informa al usuario de los siguientes datos identificativos del titular del presente sitio web:</p>
        <ul>
            <li><strong>Titular:</strong> [NOMBRE COMPLETO DEL TITULAR]</li>
            <li><strong>NIF/DNI:</strong> [NIF O DNI DEL TITULAR]</li>
            <li><strong>Domicilio:</strong> [DIRECCIÓN COMPLETA, CIUDAD, CÓDIGO POSTAL, PROVINCIA, PAÍS]</li>
            <li><strong>Correo electrónico de contacto:</strong> [EMAIL@DOMINIO.COM]</li>
            <li><strong>Teléfono de contacto:</strong> [TELÉFONO]</li>
        </ul>
        <p class="legal-nota"><i class="bi bi-info-circle"></i> Esta web se presenta como proyecto académico desarrollado en el marco de un Trabajo Final, sin finalidad comercial real. Los datos del titular se completarán por la persona responsable del proyecto en caso de despliegue en entorno productivo.</p>

        <h2>2. Objeto del sitio web</h2>
        <p>El sitio web Marketplace (en adelante, "el sitio web" o "la plataforma") tiene por objeto poner en contacto a personas usuarias que demandan determinados servicios profesionales o particulares con personas usuarias que los ofrecen, actuando como mero intermediario tecnológico, sin participar en la prestación efectiva del servicio, ni en su contratación, ni en los pagos o cobros derivados de la misma.</p>
        <p>El uso de la plataforma se rige por las presentes condiciones, por las Condiciones de Uso, por la Política de Privacidad y por la Política de Cookies, todas ellas aceptadas expresamente por el usuario en el momento del registro.</p>

        <h2>3. Aceptación del usuario</h2>
        <p>El acceso, la navegación y la utilización del sitio web atribuyen la condición de usuario, que acepta, desde el momento mismo del acceso, todas las condiciones aquí establecidas, así como las modificaciones que el titular pueda introducir.</p>
        <p>Si el usuario no estuviera de acuerdo con las condiciones expuestas, deberá abstenerse de utilizar el sitio web. Los menores de edad entre 16 y 17 años podrán hacer uso de la plataforma siempre que cuenten con el consentimiento expreso de quienes ostenten su patria potestad o tutela, en los términos previstos en las Condiciones de Uso y en la Política de Privacidad.</p>

        <h2>4. Propiedad intelectual e industrial</h2>
        <p>Todos los contenidos del sitio web (textos, fotografías, gráficos, imágenes, iconos, tecnología, software, así como su diseño gráfico y códigos fuente) son propiedad intelectual del titular o de terceros que han autorizado su uso, sin que pueda entenderse que se cede al usuario ninguno de los derechos de explotación reconocidos por la normativa vigente sobre propiedad intelectual.</p>
        <p>Las marcas, nombres comerciales o signos distintivos son titularidad del prestador o de terceros, sin que pueda entenderse que el acceso al sitio web atribuya ningún derecho sobre los mismos.</p>

        <h2>5. Responsabilidad sobre los contenidos y servicios publicados</h2>
        <p>El titular del sitio web actúa exclusivamente como intermediario y, por lo tanto, no se hace responsable de:</p>
        <ul>
            <li>La veracidad, exactitud, exhaustividad y/o autenticidad de los datos proporcionados por los usuarios prestadores de servicios.</li>
            <li>La calidad, idoneidad, legalidad o utilidad de los servicios ofertados a través de la plataforma.</li>
            <li>El cumplimiento o incumplimiento de las obligaciones que pudieran nacer entre las partes (cliente y prestador).</li>
            <li>El uso indebido que terceros pudieran hacer del sitio web.</li>
        </ul>
        <p>Los usuarios prestadores son los únicos responsables de cumplir con la normativa aplicable a su actividad, incluyendo las obligaciones fiscales, administrativas y de seguridad social que les correspondan.</p>

        <h2>6. Enlaces a sitios de terceros</h2>
        <p>El sitio web puede contener enlaces a páginas web de terceros. El titular no asume responsabilidad alguna por el contenido, informaciones o servicios que pudieran aparecer en dichos sitios, que tendrán carácter exclusivamente informativo y que en ningún caso implican relación alguna entre el titular y las personas o entidades titulares de tales contenidos o titulares de los sitios donde se encuentren.</p>

        <h2>7. Modificaciones</h2>
        <p>El titular se reserva el derecho de efectuar, sin previo aviso, las modificaciones que considere oportunas en su sitio web, pudiendo cambiar, suprimir o añadir tanto los contenidos y servicios que se presten a través de la misma como la forma en la que éstos aparezcan presentados o localizados.</p>

        <h2>8. Legislación aplicable y jurisdicción</h2>
        <p>La relación entre el titular y el usuario se regirá por la normativa española vigente. Para la resolución de cualquier controversia que pudiera surgir con motivo del acceso o uso del sitio web, las partes se someten a los Juzgados y Tribunales del domicilio del usuario, conforme al artículo 90.2 del Real Decreto Legislativo 1/2007, de 16 de noviembre, por el que se aprueba el texto refundido de la Ley General para la Defensa de los Consumidores y Usuarios.</p>

        <h2>9. Contacto</h2>
        <p>Para cualquier consulta relacionada con el presente Aviso Legal, el usuario puede dirigirse al titular a través del correo electrónico indicado en el apartado 1.</p>

    </div>

</div>

<?php include 'partials/footer.php'; ?>
<?php include 'partials/cookies-banner.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
