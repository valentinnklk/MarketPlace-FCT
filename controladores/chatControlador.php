<?php
// controladores/chatControlador.php

require_once __DIR__ . '/../modelo/chatModelo.php';

class ChatControlador {

    private ChatModelo $modelo;
    private int $usuario_id;

    public function __construct(PDO $conexion) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['usuario_id'])) {
            header('Location: ../vista/loginVista.php');
            exit;
        }

        $this->usuario_id = (int) $_SESSION['usuario_id'];
        $this->modelo     = new ChatModelo($conexion);
    }

    // ─────────────────────────────────────────────
    // ABRIR / CREAR CONVERSACIÓN
    // ─────────────────────────────────────────────
    public function abrirConversacion(): void {
        $prestador_id = (int) ($_POST['prestador_id'] ?? 0);
        $servicio_id  = (int) ($_POST['servicio_id']  ?? 0);

        if (!$prestador_id || !$servicio_id || $prestador_id === $this->usuario_id) {
            header('Location: ../vista/chat.php');
            exit;
        }

        $conv_id = $this->modelo->obtenerOCrearConversacion(
            $this->usuario_id,
            $prestador_id,
            $servicio_id
        );

        header("Location: ../vista/chat.php?conv_id=$conv_id");
        exit;
    }

    // ─────────────────────────────────────────────
    // OBTENER DATOS PARA LA VISTA (CLAVE 🔥)
    // ─────────────────────────────────────────────
    public function mostrarChat(): array {

        $conversaciones = $this->modelo->getConversacionesPorUsuario($this->usuario_id);
        $conv_activa    = null;
        $mensajes       = [];

        if (!empty($_GET['conv_id'])) {
            $conv_id     = (int) $_GET['conv_id'];
            $conv_activa = $this->modelo->getConversacionPorId($conv_id, $this->usuario_id);

            if ($conv_activa) {
                $mensajes = $this->modelo->getMensajes($conv_id);
                $this->modelo->marcarLeidos($conv_id, $this->usuario_id, $conv_activa);
            }
        }

        return [
            'conversaciones' => $conversaciones,
            'conv_activa'    => $conv_activa,
            'mensajes'       => $mensajes,
            'usuario_id'     => $this->usuario_id
        ];
    }

    // ─────────────────────────────────────────────
    // ENVIAR MENSAJE (AJAX)
    // ─────────────────────────────────────────────
    public function enviarMensaje(): void {
        header('Content-Type: application/json');

        $conv_id  = (int) ($_POST['conv_id'] ?? 0);
        $contenido = trim($_POST['contenido'] ?? '');

        if (!$conv_id || $contenido === '') {
            echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
            exit;
        }

        $conv = $this->modelo->getConversacionPorId($conv_id, $this->usuario_id);
        if (!$conv) {
            echo json_encode(['ok' => false, 'error' => 'Conversación no encontrada']);
            exit;
        }

        $id = $this->modelo->enviarMensaje($conv_id, $this->usuario_id, $contenido, $conv);

        echo json_encode([
            'ok' => true,
            'mensaje_id' => $id
        ]);
        exit;
    }

    // ─────────────────────────────────────────────
    // POLLING (AJAX)
    // ─────────────────────────────────────────────
    public function polling(): void {
        header('Content-Type: application/json');

        $conv_id  = (int) ($_GET['conv_id'] ?? 0);
        $desde_id = (int) ($_GET['desde_id'] ?? 0);

        $conv = $this->modelo->getConversacionPorId($conv_id, $this->usuario_id);
        if (!$conv) {
            echo json_encode(['ok' => false, 'error' => 'Conversación no encontrada']);
            exit;
        }

        $mensajes = $this->modelo->getMensajes($conv_id, $desde_id);
        $this->modelo->marcarLeidos($conv_id, $this->usuario_id, $conv);

        echo json_encode([
            'ok' => true,
            'mensajes' => $mensajes
        ]);
        exit;
    }

    // ─────────────────────────────────────────────
    // NO LEÍDOS (AJAX)
    // ─────────────────────────────────────────────
    public function noLeidos(): void {
        header('Content-Type: application/json');

        $total = $this->modelo->getTotalNoLeidos($this->usuario_id);

        echo json_encode([
            'ok' => true,
            'total' => $total
        ]);
        exit;
    }
}