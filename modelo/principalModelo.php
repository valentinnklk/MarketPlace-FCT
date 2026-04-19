<?php
require_once "../conexion.php";

class mostrarProducto{
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
        public function obtenerTodos(){

    $sql = "SELECT 
        productos.vendedor_id,
        productos.id,
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
    public function mostrarProductoPorId($id){
        $sql = "SELECT 
        productos.id,
        productos.titulo,
        productos.descripcion,
        productos.precio,
        productos.estado_producto,
        usuarios.nombre AS vendedor
        FROM productos
        INNER JOIN usuarios 
        ON productos.vendedor_id = usuarios.id
        WHERE productos.id = :id";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function crearProducto($vendedor_id, $titulo, $descripcion, $precio, $estado_producto) {
        $sql = "INSERT INTO productos (titulo, descripcion, precio, estado_producto, vendedor_id) 
                VALUES (:titulo, :descripcion, :precio, :estado_producto, :vendedor_id)";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':estado_producto', $estado_producto);
        $stmt->bindParam(':vendedor_id', $vendedor_id);
        
        return $stmt->execute();
    }
    public function buscarProductos(string $texto): array {
    $sql = "SELECT 
        productos.vendedor_id,
        productos.id,
        productos.titulo,
        productos.descripcion,
        productos.precio,
        productos.estado_producto,
        usuarios.nombre AS vendedor
        FROM productos
        INNER JOIN usuarios 
        ON productos.vendedor_id = usuarios.id
        WHERE productos.titulo LIKE :texto OR productos.descripcion LIKE :texto";

    $stmt = $this->conexion->prepare($sql);
    $busqueda = "%$texto%";
    $stmt->bindParam(':texto', $busqueda);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
}