<?php
// modelo/perfilModelo.php
// Solo consultas SQL del perfil.
// Recibe $conexion por constructor, no necesita require de conexion.php
 
class PerfilModelo {
 
    private PDO $conexion;
 
    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
    }
 
    // ─────────────────────────────────────────────
    // USUARIO
    // ─────────────────────────────────────────────
 
    // Datos del usuario por id
    public function getUsuario(int $id): array|false {
        $stmt = $this->conexion->prepare(
            "SELECT * FROM usuarios WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
 
    // ─────────────────────────────────────────────
    // SERVICIOS
    // ─────────────────────────────────────────────
 
    // Servicios activos que el usuario ofrece como prestador
    public function getServiciosOfrecidos(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT s.*, c.nombre AS categoria
             FROM servicios s
             JOIN categorias_servicio c ON s.categoria_id = c.id
             WHERE s.prestador_id = ? AND s.activo = 1
             ORDER BY s.fecha_publicacion DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }
 
    // ─────────────────────────────────────────────
    // CONTRATOS (historial de servicios contratados)
    // ─────────────────────────────────────────────
 
    // Contratos realizados por el usuario como cliente
    public function getContratosComoCliente(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT co.*, s.titulo, s.precio, s.unidad_cobro, u.nombre AS prestador
             FROM contratos co
             JOIN servicios s ON co.servicio_id = s.id
             JOIN usuarios  u ON s.prestador_id = u.id
             WHERE co.cliente_id = ?
             ORDER BY co.fecha_contrato DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }
 
    // Contratos recibidos por el usuario como prestador
    public function getContratosComoPrestador(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT co.*, s.titulo, s.precio, s.unidad_cobro, u.nombre AS cliente
             FROM contratos co
             JOIN servicios s ON co.servicio_id  = s.id
             JOIN usuarios  u ON co.cliente_id   = u.id
             WHERE s.prestador_id = ?
             ORDER BY co.fecha_contrato DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }
 
    // ─────────────────────────────────────────────
    // FAVORITOS
    // ─────────────────────────────────────────────
 
    // Servicios marcados como favoritos por el usuario
    public function getFavoritos(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT s.*, u.nombre AS prestador, f.fecha_agregado
             FROM favoritos f
             JOIN servicios s ON f.servicio_id  = s.id
             JOIN usuarios  u ON s.prestador_id = u.id
             WHERE f.usuario_id = ?
             ORDER BY f.fecha_agregado DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }
 
    // Eliminar un servicio de favoritos
    public function eliminarFavorito(int $idUsuario, int $idServicio): void {
        $stmt = $this->conexion->prepare(
            "DELETE FROM favoritos WHERE usuario_id = ? AND servicio_id = ?"
        );
        $stmt->execute([$idUsuario, $idServicio]);
    }
 
    // ─────────────────────────────────────────────
    // VALORACIONES
    // ─────────────────────────────────────────────
 
    // Valoraciones recibidas por el usuario (como prestador)
    public function getValoracionesRecibidas(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT v.*, u.nombre AS autor, u.avatar_url AS autor_avatar,
                    s.titulo AS servicio
             FROM valoraciones v
             JOIN usuarios  u  ON v.revisor_id   = u.id
             JOIN contratos co ON v.contrato_id  = co.id
             JOIN servicios s  ON co.servicio_id = s.id
             WHERE v.revisado_id = ?
             ORDER BY v.fecha DESC"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }
 
    // Puntuación media y total de valoraciones recibidas
    public function getValoracionMedia(int $idUsuario): array {
        $stmt = $this->conexion->prepare(
            "SELECT
                ROUND(AVG(puntuacion), 1) AS media,
                COUNT(*)                 AS total
             FROM valoraciones
             WHERE revisado_id = ?"
        );
        $stmt->execute([$idUsuario]);
        return $stmt->fetch() ?: ['media' => null, 'total' => 0];
    }
}