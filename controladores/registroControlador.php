<?php
require_once __DIR__ . '/../MODELO/usuariosModelo.php';

$mensaje = '';
$exito = false;

if (isset($_POST['registroEnviar'])) {
    
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $ubicacion = $_POST['ubicacion'];
    
    if (nombreExiste($nombre)) {
        $mensaje = "El nombre ya está en uso";
    }
    elseif (emailExiste($email)) {
        $mensaje = "El email ya está registrado";
    }
    else {
        $resultado = insertarUsuario($nombre, $email, $password, $ubicacion, false);
        
        if ($resultado) {
            // Si el registro es exitoso, redirige al home
            header("Location: ../VISTA/home.php");
            exit();
        } else {
            $mensaje = "Error al registrar el usuario";
        }
    }
}

// Solo llega aquí si hubo error
$tipo = $exito ? 'exito' : 'error';
$mensajeCodificado = urlencode($mensaje);

header("Location: ../VISTA/registroVista.php?respuestaCreacionUsu=$mensajeCodificado&creacion=$tipo");
exit();
?>