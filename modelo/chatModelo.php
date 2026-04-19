<?php
// modelo/chatModelo.php

class ChatModelo {

    private PDO $conn;

    public function __construct(PDO $conexion) {
        $this->conn = $conexion;
    }

    // ─────────────────────────────────────────────
    // CONVERSACIONES
    // ─────────────────────────────────────────────

    /**
     * Busca una conversación existente entre cliente y prestador para un servicio.
     * Si no existe la crea y devuelve el id.
     */
    public function obtenerOCrearConversacion(int $cliente_id, int $prestador_id, int $servicio_id): int {
        $stmt = $this->conn->prepare(
            "SELECT id FROM conversaciones
             WHERE cliente_id = ? AND prestador_id = ? AND servicio_id = ?
             LIMIT 1"
        );
        $stmt->execute([$cliente_id, $prestador_id, $servicio_id]);
        $fila = $stmt->fetch();

        if ($fila) return (int) $fila['id'];

        $stmt = $this->conn->prepare(
            "INSERT INTO conversaciones (cliente_id, prestador_id, servicio_id, fecha_inicio)
             VALUES (?, ?, ?, NOW())"
        );
        $stmt->execute([$cliente_id, $prestador_id, $servicio_id]);
        return (int) $this->conn->lastInsertId();
    }

    /**
     * Devuelve todas las conversaciones del usuario (como cliente O prestador)
     * con los datos del otro participante y del servicio.
     */
    public function getConversacionesPorUsuario(int $usuario_id): array {
        $stmt = $this->conn->prepare(
            "SELECT
                c.id,
                c.servicio_id,
                c.ultimo_mensaje,
                c.fecha_ultimo_mensaje,
                c.total_mensajes,
                s.titulo AS servicio_titulo,
                CASE WHEN c.cliente_id = :uid1 THEN p.id         ELSE cl.id         END AS otro_id,
                CASE WHEN c.cliente_id = :uid2 THEN p.nombre     ELSE cl.nombre     END AS otro_nombre,
                CASE WHEN c.cliente_id = :uid3 THEN p.avatar_url ELSE cl.avatar_url END AS otro_avatar,
                CASE WHEN c.cliente_id = :uid4
                     THEN c.no_leidos_cliente
                     ELSE c.no_leidos_prestador
                END AS no_leidos
             FROM conversaciones c
             JOIN usuarios cl ON cl.id = c.cliente_id
             JOIN usuarios p  ON p.id  = c.prestador_id
             LEFT JOIN servicios s ON s.id = c.servicio_id
             WHERE c.cliente_id = :uid5 OR c.prestador_id = :uid6
             ORDER BY c.fecha_ultimo_mensaje DESC"
        );
        $stmt->execute([
            ':uid1' => $usuario_id, ':uid2' => $usuario_id,
            ':uid3' => $usuario_id, ':uid4' => $usuario_id,
            ':uid5' => $usuario_id, ':uid6' => $usuario_id,
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Devuelve una conversación verificando que el usuario sea participante.
     */
    public function getConversacionPorId(int $conv_id, int $usuario_id): array|false {
        $stmt = $this->conn->prepare(
            "SELECT c.*,
                cl.nombre     AS cliente_nombre,   cl.avatar_url AS cliente_avatar,
                p.nombre      AS prestador_nombre, p.avatar_url  AS prestador_avatar,
                s.titulo      AS servicio_titulo
             FROM conversaciones c
             JOIN usuarios cl ON cl.id = c.cliente_id
             JOIN usuarios p  ON p.id  = c.prestador_id
             LEFT JOIN servicios s ON s.id = c.servicio_id
             WHERE c.id = ?
               AND (c.cliente_id = ? OR c.prestador_id = ?)
             LIMIT 1"
        );
        $stmt->execute([$conv_id, $usuario_id, $usuario_id]);
        return $stmt->fetch();
    }

    // ─────────────────────────────────────────────
    // MENSAJES
    // ─────────────────────────────────────────────

    /**
     * Devuelve los mensajes de una conversación.
     * $desde_id > 0 solo trae los nuevos (polling AJAX).
     */
    public function getMensajes(int $conv_id, int $desde_id = 0): array {
        $stmt = $this->conn->prepare(
            "SELECT m.id, m.remitente_id, m.contenido, m.leido, m.fecha_envio,
                    u.nombre AS remitente_nombre, u.avatar_url AS remitente_avatar
             FROM mensajes m
             JOIN usuarios u ON u.id = m.remitente_id
             WHERE m.conversacion_id = ? AND m.id > ?
             ORDER BY m.fecha_envio ASC"
        );
        $stmt->execute([$conv_id, $desde_id]);
        return $stmt->fetchAll();
    }

    /**
     * Inserta un mensaje y actualiza el resumen de la conversación.
     */
    public function enviarMensaje(int $conv_id, int $remitente_id, string $contenido, array $conv): int {
        $stmt = $this->conn->prepare(
            "INSERT INTO mensajes (conversacion_id, remitente_id, contenido, leido, fecha_envio)
             VALUES (?, ?, ?, 0, NOW())"
        );
        $stmt->execute([$conv_id, $remitente_id, $contenido]);
        $mensaje_id = (int) $this->conn->lastInsertId();

        // Incrementar no_leidos del destinatario
        $es_cliente   = ($remitente_id === (int) $conv['cliente_id']);
        $campo_leidos = $es_cliente ? 'no_leidos_prestador' : 'no_leidos_cliente';

        $resumen = mb_substr($contenido, 0, 80);
        $stmt = $this->conn->prepare(
            "UPDATE conversaciones
             SET ultimo_mensaje       = ?,
                 fecha_ultimo_mensaje = NOW(),
                 total_mensajes       = total_mensajes + 1,
                 $campo_leidos        = $campo_leidos + 1
             WHERE id = ?"
        );
        $stmt->execute([$resumen, $conv_id]);

        return $mensaje_id;
    }

    /**
     * Marca como leídos los mensajes recibidos y resetea el contador.
     */
    public function marcarLeidos(int $conv_id, int $usuario_id, array $conv): void {
        $stmt = $this->conn->prepare(
            "UPDATE mensajes
             SET leido = 1
             WHERE conversacion_id = ? AND remitente_id != ? AND leido = 0"
        );
        $stmt->execute([$conv_id, $usuario_id]);

        $es_cliente   = ($usuario_id === (int) $conv['cliente_id']);
        $campo_leidos = $es_cliente ? 'no_leidos_cliente' : 'no_leidos_prestador';

        $stmt = $this->conn->prepare(
            "UPDATE conversaciones SET $campo_leidos = 0 WHERE id = ?"
        );
        $stmt->execute([$conv_id]);
    }

    /**
     * Total de no leídos del usuario en todas sus conversaciones.
     * Usa los contadores de la tabla (más rápido que COUNT en mensajes).
     */
    public function getTotalNoLeidos(int $usuario_id): int {
        $stmt = $this->conn->prepare(
            "SELECT
                SUM(CASE WHEN cliente_id   = :uid1 THEN no_leidos_cliente   ELSE 0 END) +
                SUM(CASE WHEN prestador_id = :uid2 THEN no_leidos_prestador ELSE 0 END) AS total
             FROM conversaciones
             WHERE cliente_id = :uid3 OR prestador_id = :uid4"
        );
        $stmt->execute([
            ':uid1' => $usuario_id, ':uid2' => $usuario_id,
            ':uid3' => $usuario_id, ':uid4' => $usuario_id,
        ]);
        $res = $stmt->fetch();
        return (int) ($res['total'] ?? 0);
    }
}
