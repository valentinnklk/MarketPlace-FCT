<?php
require_once "../modelo/principalModelo.php";
class ProductoController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function mostrarProducto() {
        $producto = new mostrarProducto($this->conexion);
        return $producto->mostrarProducto();
    }
    public function mostrarProductoPorId($id) {
        $productos = new mostrarProducto($this->conexion);
        return $productos->mostrarProductoPorId($id);
    }
}   
?>