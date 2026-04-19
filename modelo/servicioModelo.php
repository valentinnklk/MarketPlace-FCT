<?php
class ServicioModelo {
    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // NUEVO: Este método soluciona el error de tu imagen
    public function getServicios(): array {
        $stmt = $this->conexion->query(
            "SELECT s.*, cs.nombre AS categoria_nombre, u.nombre AS prestador, 0 as valoracion_media
             FROM servicios s
             JOIN categorias_servicio cs ON s.categoria_id = cs.id
             JOIN usuarios u ON s.prestador_id = u.id
             WHERE s.activo = 1"
        );
        return $stmt->fetchAll();
    }

    public function getCategorias(): array {
        $stmt = $this->conexion->query("SELECT * FROM categorias_servicio ORDER BY nombre");
        return $stmt->fetchAll();
    }

    public function guardarServicio(array $datos): int {
        $stmt = $this->conexion->prepare(
            "INSERT INTO servicios (prestador_id, categoria_id, titulo, descripcion, precio, unidad_cobro, ubicacion, fecha_publicacion)
             VALUES (:prestador_id, :categoria_id, :titulo, :descripcion, :precio, :unidad_cobro, :ubicacion, NOW())"
        );
        $stmt->execute([
            ':prestador_id' => $datos['prestador_id'],
            ':categoria_id' => $datos['categoria_id'],
            ':titulo'       => $datos['titulo'],
            ':descripcion'  => $datos['descripcion'],
            ':precio'       => $datos['precio'],
            ':unidad_cobro' => $datos['unidad_cobro'],
            ':ubicacion'    => $datos['ubicacion']
        ]);
        return (int) $this->conexion->lastInsertId();
    }
}