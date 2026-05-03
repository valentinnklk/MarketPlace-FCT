<?php
require_once "../modelo/usuario.php";
class UsuarioController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function mostrarUsuario() {
        $usuario = new Usuario($this->conexion);
        return $usuario->mostrarUsuario();
    }
    public function obtenerPorId($id) {
    $usuario = new Usuario($this->conexion);
    return $usuario->obtenerPorId($id);
}

public function obtenerServiciosPorUsuario($id) {
    $usuario = new Usuario($this->conexion);
    return $usuario->obtenerServiciosPorUsuario($id);
}

}
?>