<?php
require_once __DIR__ . '/../MODELO/usuariosModelo.php';
//bratu

$mensaje = '';
$exito = false;

// Procesar usuario normal
if (isset($_POST['usuarioNormal'])) {
    
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
            $mensaje = "Usuario normal creado correctamente";
            $exito = true;
        } else {
            $mensaje = "Error al crear el usuario";
        }
    }
}

// Procesar administrador
if (isset($_POST['usuarioAdmin'])) {
    
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
        $resultado = insertarUsuario($nombre, $email, $password, $ubicacion, true);
        
        if ($resultado) {
            $mensaje = "Administrador creado correctamente";
            $exito = true;
        } else {
            $mensaje = "Error al crear el administrador";
        }
    }
}

// Redirigir de vuelta a la vista con los mensajes en la URL
$tipo = $exito ? 'exito' : 'error';
$mensajeCodificado = urlencode($mensaje);

header("Location: ../VISTA/panelAdministracion.php?respuestaCreacionUsu=$mensajeCodificado&creacion=$tipo");
exit();
?>