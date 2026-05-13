<?php
session_start();
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
if (isset($_POST['eliminarServicio'])) {
    $servicio_id = (int) $_POST['servicio_id'];

    eliminarServicioPorId($servicio_id);

    header("Location: ../VISTA/panelAdministracion.php?respuestaCreacionUsu="
        . urlencode("Servicio eliminado correctamente.")
        . "&creacion=exito");
    exit();
}if (isset($_POST['eliminarUsuario'])) {
    $usuario_id = (int) $_POST['usuario_id'];

    // Evitar eliminar tu propia cuenta
    if ($usuario_id == $_SESSION['usuario_id']) {
        header("Location: ../VISTA/panelAdministracion.php?respuestaCreacionUsu="
            . urlencode("No puedes eliminar tu propia cuenta.")
            . "&creacion=error");
        exit();
    }

    eliminarUsuarioPorId($usuario_id);

    header("Location: ../VISTA/panelAdministracion.php?respuestaCreacionUsu="
        . urlencode("Usuario eliminado correctamente.")
        . "&creacion=exito");
    exit();
}
if (isset($_POST['eliminarReporte'])) {
    $reporte_id = (int) $_POST['reporte_id'];

    eliminarReportePorId($reporte_id);

    header("Location: ../VISTA/panelAdministracion.php?respuestaCreacionUsu="
        . urlencode("Reporte eliminado correctamente.")
        . "&creacion=exito");
    exit();
}
if (isset($_POST['marcarResuelto'])) {
    $reporte_id = (int) $_POST['reporte_id'];

    actualizarEstadoReporte($reporte_id, 'resuelto');

    header("Location: ../VISTA/panelAdministracion.php");
    exit();
}

// En panelAdministracionControlador.php añade este bloque
if (isset($_POST['cambiarEstadoReporte'])) {
    $reporte_id = (int) $_POST['reporte_id'];

    // Necesitas una función en el modelo que devuelva el reporte por ID
    $reporte = obtenerReportePorId($reporte_id);

    if ($reporte) {
        $nuevoEstado = ($reporte['estado'] === 'pendiente')
            ? 'resuelto'
            : 'pendiente';

        actualizarEstadoReporte($reporte_id, $nuevoEstado);
    }

    header("Location: ../VISTA/panelAdministracion.php");
    exit();
}
// Redirigir de vuelta a la vista con los mensajes en la URL
$tipo = $exito ? 'exito' : 'error';
$mensajeCodificado = urlencode($mensaje);

header("Location: ../VISTA/panelAdministracion.php?respuestaCreacionUsu=$mensajeCodificado&creacion=$tipo");
exit();
?>