<?php
session_start();
require_once __DIR__ . '/../MODELO/usuariosModelo.php';

$mensaje = '';
$exito = false;

if (isset($_POST['loginEnviar'])) {
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $usuario = obtenerUsuarioPorEmail($email);
    
    if ($usuario) {
        if (password_verify($password, $usuario['contraseña_hash'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['es_admin'] = $usuario['es_administrador'];
            
            $exito = true;
            $mensaje = "Bienvenido, " . $usuario['nombre'];
            
            if ($usuario['es_administrador']) {
                header("Location: ../VISTA/home.php");
            } else {
                header("Location: ../VISTA/home.php");
            }
            exit();
        } else {
            $mensaje = "Contraseña incorrecta";
        }
    } else {
        $mensaje = "No existe una cuenta con ese email";
    }
}

$tipo = $exito ? 'exito' : 'error';
$mensajeCodificado = urlencode($mensaje);

header("Location: ../VISTA/loginVista.php?server_msg=$mensajeCodificado&creacion=$tipo");
exit();
?>