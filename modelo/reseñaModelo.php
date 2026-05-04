<?php
// modelo/reseñaModelo.php

// Esquema:
//   valoraciones(id, contrato_id UNIQUE, revisor_id, revisado_id,
//                puntuacion tinyint 1-5, comentario, fecha)
//
// Regla de negocio: solo se puede crear una valoración si el contrato
// asociado está en estado 'completado' y todavía no tiene valoración.
//
// Al crear una valoración actualizamos también `servicios.valoracion_media`
// (columna denormalizada que sí existe en el esquema).

class ReseñaModelo {

    private PDO $conn;

    public function __construct(PDO $conexion) {
        $this->conn = $conexion;
    }

    // ─────────────────────────────────────────────
    // REGLAS DE NEGOCIO
    // ─────────────────────────────────────────────

    /**
     * Comprueba si un cliente puede valorar un contrato.
     * Devuelve ['puede' => bool, 'motivo' => string, 'datos' => array|null]
     */
    public function puedeReseñar(int $contrato_id, int $cliente_id): array {
        $stmt = $this->conn->prepare(
            "SELECT c.id, c.cliente_id, c.estado, c.servicio_id,
                    s.prestador_id, s.titulo,
                    (SELECT v.id FROM valoraciones v WHERE v.contrato_id = c.id LIMIT 1) AS reseña_id
             FROM contratos c
             JOIN servicios s ON s.id = c.servicio_id
             WHERE c.id = ?
             LIMIT 1"
        );
        $stmt->execute([$contrato_id]);
        $c = $stmt->fetch();

        if (!$c) {
            return ['puede' => false, 'motivo' => 'El contrato no existe.', 'datos' => null];
        }
        if ((int) $c['cliente_id'] !== $cliente_id) {
            return ['puede' => false, 'motivo' => 'No puedes valorar un contrato que no te pertenece.', 'datos' => null];
        }
        if ($c['estado'] !== 'completado') {
            return ['puede' => false, 'motivo' => 'Solo puedes valorar cuando el servicio esté marcado como completado.', 'datos' => $c];
        }
        if (!empty($c['reseña_id'])) {
            return ['puede' => false, 'motivo' => 'Ya has valorado este servicio.', 'datos' => $c];
        }
        return ['puede' => true, 'motivo' => '', 'datos' => $c];
    }

    // ─────────────────────────────────────────────
    // ALTA DE VALORACIÓN
    // ─────────────────────────────────────────────

    /**
     * Crea una valoración y actualiza la valoración media del servicio.
     * Devuelve el id creado o 0 si falla.
     */
    public function crearReseña(
        int $contrato_id,
        int $cliente_id,
        int $puntuacion,
        ?string $comentario
    ): int {
        if ($puntuacion < 1 || $puntuacion > 5) return 0;

        $check = $this->puedeReseñar($contrato_id, $cliente_id);
        if (!$check['puede']) return 0;

        $datos        = $check['datos'];
        $prestador_id = (int) $datos['prestador_id'];
        $servicio_id  = (int) $datos['servicio_id'];

        $comentario = $comentario !== null ? trim($comentario) : null;
        if ($comentario === '') $comentario = null;

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO valoraciones
                    (contrato_id, revisor_id, revisado_id, puntuacion, comentario, fecha)
                 VALUES (?, ?, ?, ?, ?, NOW())"
            );
            $stmt->execute([
                $contrato_id, $cliente_id, $prestador_id,
                $puntuacion, $comentario,
            ]);
            $id = (int) $this->conn->lastInsertId();

            // Recalculamos la media denormalizada del servicio
            $this->recalcularMediaServicio($servicio_id);

            $this->conn->commit();
            return $id;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return 0;
        }
    }

    /**
     * Recalcula y actualiza `servicios.valoracion_media`
     * con la media de todas las valoraciones recibidas
     * en ese servicio concreto.
     */
    private function recalcularMediaServicio(int $servicio_id): void {
        $stmt = $this->conn->prepare(
            "UPDATE servicios s
                LEFT JOIN (
                    SELECT c.servicio_id, ROUND(AVG(v.puntuacion), 1) AS media
                    FROM valoraciones v
                    JOIN contratos c ON c.id = v.contrato_id
                    WHERE c.servicio_id = ?
                    GROUP BY c.servicio_id
                ) t ON t.servicio_id = s.id
             SET s.valoracion_media = COALESCE(t.media, 0.0)
             WHERE s.id = ?"
        );
        $stmt->execute([$servicio_id, $servicio_id]);
    }

    // ─────────────────────────────────────────────
    // CONSULTAS
    // ─────────────────────────────────────────────

    /**
     * Datos de un contrato + valoración asociada (si la hubiera).
     * Útil para rellenar el formulario.
     */
    public function getContratoConReseña(int $contrato_id): array|false {
        $stmt = $this->conn->prepare(
            "SELECT c.id, c.cliente_id, c.servicio_id, c.estado,
                    c.precio_acordado, c.fecha_contrato, c.fecha_servicio,
                    s.titulo, s.prestador_id, s.unidad_cobro,
                    u.nombre AS prestador_nombre,
                    v.id         AS reseña_id,
                    v.puntuacion,
                    v.comentario,
                    v.fecha      AS fecha_reseña
             FROM contratos c
             JOIN servicios s ON s.id = c.servicio_id
             JOIN usuarios  u ON u.id = s.prestador_id
             LEFT JOIN valoraciones v ON v.contrato_id = c.id
             WHERE c.id = ?
             LIMIT 1"
        );
        $stmt->execute([$contrato_id]);
        return $stmt->fetch();
    }

    /**
     * Total de valoraciones recibidas por un prestador.
     */
    public function getTotalReseñasRecibidas(int $prestador_id): int {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total FROM valoraciones WHERE revisado_id = ?"
        );
        $stmt->execute([$prestador_id]);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Puntuación media (1 decimal) y total de valoraciones recibidas por un prestador.
     */
    public function getEstadisticasPrestador(int $prestador_id): array {
        $stmt = $this->conn->prepare(
            "SELECT ROUND(AVG(puntuacion), 1) AS media,
                    COUNT(*)                  AS total
             FROM valoraciones
             WHERE revisado_id = ?"
        );
        $stmt->execute([$prestador_id]);
        $row = $stmt->fetch();
        return [
            'media' => $row['media'] !== null ? (float) $row['media'] : 0.0,
            'total' => (int) ($row['total'] ?? 0),
        ];
    }

    /**
     * Listado de valoraciones recibidas (con datos del revisor y del servicio).
     */
    public function getReseñasRecibidas(int $prestador_id, int $limite = 20): array {
        $stmt = $this->conn->prepare(
            "SELECT v.id, v.puntuacion, v.comentario, v.fecha,
                    u.nombre     AS autor,
                    u.avatar_url AS autor_avatar,
                    s.titulo     AS servicio,
                    s.id         AS servicio_id
             FROM valoraciones v
             JOIN usuarios  u ON u.id = v.revisor_id
             JOIN contratos c ON c.id = v.contrato_id
             JOIN servicios s ON s.id = c.servicio_id
             WHERE v.revisado_id = ?
             ORDER BY v.fecha DESC
             LIMIT " . (int) $limite
        );
        $stmt->execute([$prestador_id]);
        return $stmt->fetchAll();
    }
}
