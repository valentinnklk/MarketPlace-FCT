<?php
// controladores/chatControlador.php
//
// Reescrito contra el esquema REAL (tablas `chats` y `mensajes` con chat_id).
// Nombres de parámetros de URL:
//   chat_id  (antes era conv_id)

require_once __DIR__ . '/../modelo/chatModelo.php';

class ChatControlador {

    private ChatModelo $modelo;
    private int $usuario_id;

    public function __construct(PDO $conexion) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['usuario_id'])) {
            header('Location: loginVista.php');
            exit;
        }

        $this->usuario_id = (int) $_SESSION['usuario_id'];
        $this->modelo     = new ChatModelo($conexion);
    }

    // ─────────────────────────────────────────────
    // ABRIR / CREAR CHAT
    // ─────────────────────────────────────────────
    public function abrirChat(): void {
        $prestador_id = (int) ($_POST['prestador_id'] ?? 0);
        $servicio_id  = (int) ($_POST['servicio_id']  ?? 0);

        if (!$prestador_id || !$servicio_id || $prestador_id === $this->usuario_id) {
            header('Location: chat.php');
            exit;
        }

        $chat_id = $this->modelo->obtenerOCrearChat(
            $this->usuario_id,   // cliente (el que inicia)
            $prestador_id,
            $servicio_id
        );

        header("Location: chat.php?chat_id=$chat_id");
        exit;
    }

    // ─────────────────────────────────────────────
    // OBTENER DATOS PARA LA VISTA
    // ─────────────────────────────────────────────
    public function mostrarChat(): array {

        $chats       = $this->modelo->getChatsPorUsuario($this->usuario_id);
        $chat_activo = null;
        $mensajes    = [];

        if (!empty($_GET['chat_id'])) {
            $chat_id     = (int) $_GET['chat_id'];
            $chat_activo = $this->modelo->getChatPorId($chat_id, $this->usuario_id);

            if ($chat_activo) {
                $mensajes = $this->modelo->getMensajes($chat_id);
                $this->modelo->marcarLeidos($chat_id, $this->usuario_id);
            }
        }

        return [
            'chats'       => $chats,
            'chat_activo' => $chat_activo,
            'mensajes'    => $mensajes,
            'usuario_id'  => $this->usuario_id,
        ];
    }

    // ─────────────────────────────────────────────
    // ENVIAR MENSAJE (AJAX)
    // ─────────────────────────────────────────────
    public function enviarMensaje(): void {
        header('Content-Type: application/json');

        $chat_id   = (int) ($_POST['chat_id'] ?? 0);
        $contenido = trim($_POST['contenido'] ?? '');

        if (!$chat_id || $contenido === '') {
            echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
            exit;
        }

        $chat = $this->modelo->getChatPorId($chat_id, $this->usuario_id);
        if (!$chat) {
            echo json_encode(['ok' => false, 'error' => 'Chat no encontrado']);
            exit;
        }

        $id = $this->modelo->enviarMensaje($chat_id, $this->usuario_id, $contenido);

        echo json_encode([
            'ok'         => $id > 0,
            'mensaje_id' => $id,
        ]);
        exit;
    }

    // ─────────────────────────────────────────────
    // POLLING (AJAX)
    // ─────────────────────────────────────────────
    public function polling(): void {
        header('Content-Type: application/json');

        $chat_id  = (int) ($_GET['chat_id']  ?? 0);
        $desde_id = (int) ($_GET['desde_id'] ?? 0);

        $chat = $this->modelo->getChatPorId($chat_id, $this->usuario_id);
        if (!$chat) {
            echo json_encode(['ok' => false, 'error' => 'Chat no encontrado']);
            exit;
        }

        $mensajes = $this->modelo->getMensajes($chat_id, $desde_id);
        $this->modelo->marcarLeidos($chat_id, $this->usuario_id);

        echo json_encode([
            'ok'       => true,
            'mensajes' => $mensajes,
        ]);
        exit;
    }

    // ─────────────────────────────────────────────
    // NO LEÍDOS (AJAX — badge navbar)
    // ─────────────────────────────────────────────
    public function noLeidos(): void {
        header('Content-Type: application/json');

        $total = $this->modelo->getTotalNoLeidos($this->usuario_id);

        echo json_encode([
            'ok'    => true,
            'total' => $total,
        ]);
        exit;
    }
}
