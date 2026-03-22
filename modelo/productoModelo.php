<?php
// modelo/productoModelo.php
// Solo consultas SQL de productos.

class ProductoModelo {

    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // Categorías activas desde la tabla categorias
    public function getCategorias(): array {
        $stmt = $this->conexion->query(
            "SELECT * FROM categorias WHERE activa = 1 ORDER BY nombre"
        );
        return $stmt->fetchAll();
    }

    // Guardar nuevo producto y devolver su id
    public function guardarProducto(array $datos): int {
        $stmt = $this->conexion->prepare(
            "INSERT INTO productos
                (vendedor_id, titulo, descripcion, precio, estado_producto, ubicacion, fecha_publicacion)
             VALUES
                (:vendedor_id, :titulo, :descripcion, :precio, :estado_producto, :ubicacion, NOW())"
        );
        $stmt->execute([
            ':vendedor_id'     => $datos['vendedor_id'],
            ':titulo'          => $datos['titulo'],
            ':descripcion'     => $datos['descripcion'],
            ':precio'          => $datos['precio'],
            ':estado_producto' => $datos['estado_producto'],
            ':ubicacion'       => $datos['ubicacion'],
        ]);
        return (int) $this->conexion->lastInsertId();
    }

    // Asociar el producto a una categoría en producto_categorias
    public function guardarCategoria(int $productoId, int $categoriaId): void {
        $stmt = $this->conexion->prepare(
            "INSERT INTO producto_categorias (producto_id, categoria_id, es_principal)
             VALUES (?, ?, 1)"
        );
        $stmt->execute([$productoId, $categoriaId]);
    }
}
