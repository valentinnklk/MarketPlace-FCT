<?php
require_once "../conexion.php";

class mostrarProducto{
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
        public function mostrarProducto(){

    $sql = "SELECT 
        
        productos.titulo,
        productos.descripcion,
        productos.precio,
        productos.estado_producto,
        usuarios.nombre AS vendedor
        FROM productos
        INNER JOIN usuarios 
        ON productos.vendedor_id = usuarios.id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
}