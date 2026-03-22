<?php
// modelo/perfilModelo.php
// Solo consultas SQL del perfil.
// Recibe $conexion por constructor, no necesita require de conexion.php

class PerfilModelo {

    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // Datos del usuario por id
    public function getUsuario(int $id): array|false {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM usuarios WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Productos activos del usuario (en venta)
    public function getProductosEnVenta(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM productos
             WHERE vendedor_id = ? AND activo = 1
             ORDER BY fecha_publicacion DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    // Compras realizadas por el usuario
    public function getCompras(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT pe.*, pr.titulo, pr.precio, u.nombre AS vendedor
             FROM pedidos pe
             JOIN productos pr ON pe.producto_id = pr.id
             JOIN usuarios  u  ON pr.vendedor_id = u.id
             WHERE pe.comprador_id = ?
             ORDER BY pe.fecha_creacion DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    // Favoritos del usuario
    public function getFavoritos(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT pr.*, u.nombre AS vendedor
             FROM favoritos f
             JOIN productos pr ON f.producto_id  = pr.id
             JOIN usuarios  u  ON pr.vendedor_id = u.id
             WHERE f.usuario_id = ?
             ORDER BY f.fecha_agregado DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    // Reseñas recibidas
    public function getResenas(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT r.*, u.nombre AS autor, pr.titulo AS producto
             FROM reseñas r
             JOIN usuarios  u  ON r.revisor_id   = u.id
             JOIN pedidos   pe ON r.pedido_id    = pe.id
             JOIN productos pr ON pe.producto_id = pr.id
             WHERE r.revisado_id = ?
             ORDER BY r.fecha DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    // Eliminar favorito
    public function eliminarFavorito(int $idUsuario, int $idProducto): void {
        $stmt = $this->conexion->prepare(
            "DELETE FROM favoritos WHERE usuario_id = ? AND producto_id = ?"
        );
        $stmt->execute([$idUsuario, $idProducto]);
    }
}
