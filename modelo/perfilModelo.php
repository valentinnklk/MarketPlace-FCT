<?php
// modelo/perfilModelo.php
//
// Adaptado al esquema REAL del proyecto según marketplace (2).sql:
//   - tabla servicios: usa 'unidad_cobro' (NO 'tipo_precio' ni 'unidad_precio')
//   - tabla valoraciones: nombre correcto según SQL

class PerfilModelo {

    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }

    // ---------------------------------------------
    // USUARIO
    // ---------------------------------------------
    public function getUsuario(int $id): array|false {
        $stmt = $this->conexion->prepare(
            "SELECT id, nombre, email, ubicacion, avatar_url,
                    es_administrador, fecha_registro, tiempo_respuesta
             FROM usuarios WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ---------------------------------------------
    // SERVICIOS QUE OFRECE EL USUARIO (como prestador)
    // ---------------------------------------------
    public function getServiciosOfrecidos(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT s.id, s.titulo, s.descripcion, s.precio, s.unidad_cobro,
                    s.valoracion_media, s.activo, s.fecha_publicacion,
                    c.nombre AS categoria
             FROM servicios s
             JOIN categorias_servicio c ON s.categoria_id = c.id
             WHERE s.prestador_id = ? AND s.activo = 1
             ORDER BY s.fecha_publicacion DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    // ---------------------------------------------
    // CONTRATOS como CLIENTE
    // ---------------------------------------------
    public function getContratosComoCliente(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT co.id, co.servicio_id, co.precio_acordado, co.estado,
                    co.fecha_contrato, co.fecha_servicio,
                    s.titulo, s.precio, s.unidad_cobro,
                    u.nombre AS prestador,
                    v.id     AS reseña_id
             FROM contratos co
             JOIN servicios s ON co.servicio_id = s.id
             JOIN usuarios  u ON s.prestador_id = u.id
             LEFT JOIN valoraciones v ON v.contrato_id = co.id AND v.revisor_id = co.cliente_id
             WHERE co.cliente_id = ?
             ORDER BY co.fecha_contrato DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    // ---------------------------------------------
    // CONTRATOS como PRESTADOR (pedidos recibidos)
    // ---------------------------------------------
    public function getContratosComoPrestador(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT co.id, co.servicio_id, co.precio_acordado, co.estado,
                    co.fecha_contrato, co.fecha_servicio,
                    s.titulo, s.unidad_cobro,
                    u.nombre AS cliente
             FROM contratos co
             JOIN servicios s ON co.servicio_id = s.id
             JOIN usuarios  u ON co.cliente_id  = u.id
             WHERE s.prestador_id = ?
             ORDER BY co.fecha_contrato DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    // ---------------------------------------------
    // FAVORITOS
    // ---------------------------------------------
    public function getFavoritos(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT s.id, s.titulo, s.precio, s.unidad_cobro, s.activo,
                    u.nombre AS prestador,
                    f.fecha_agregado
             FROM favoritos f
             JOIN servicios s ON f.servicio_id  = s.id
             JOIN usuarios  u ON s.prestador_id = u.id
             WHERE f.usuario_id = ?
             ORDER BY f.fecha_agregado DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    public function eliminarFavorito(int $idUsuario, int $idServicio): void {
        $stmt = $this->conexion->prepare(
            "DELETE FROM favoritos WHERE usuario_id = ? AND servicio_id = ?"
        );
        $stmt->execute([$idUsuario, $idServicio]);
    }

    // ---------------------------------------------
    // VALORACIONES RECIBIDAS (como prestador)
    // ---------------------------------------------
    public function getValoracionesRecibidas(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT v.id, v.puntuacion, v.comentario, v.fecha,
                    u.nombre     AS autor,
                    u.avatar_url AS autor_avatar,
                    s.titulo     AS servicio
             FROM valoraciones v
             JOIN usuarios   u  ON v.revisor_id   = u.id
             JOIN contratos co  ON v.contrato_id  = co.id
             JOIN servicios  s  ON co.servicio_id = s.id
             WHERE v.revisado_id = ?
             ORDER BY v.fecha DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    public function getValoracionMedia(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT ROUND(AVG(puntuacion), 1) AS media,
                    COUNT(*)                  AS total
             FROM valoraciones
             WHERE revisado_id = ?"
        );
        $stmt->execute([$idUsuario]);
        $row = $stmt->fetch();
        return [
            'media' => $row['media'] !== null ? (float) $row['media'] : 0.0,
            'total' => (int) ($row['total'] ?? 0),
        ];
    }
}