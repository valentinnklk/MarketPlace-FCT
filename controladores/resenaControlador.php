<?php
// controladores/resenaControlador.php

require_once __DIR__ . '/../modelo/resenaModelo.php';

class ReseñaControlador {

    private ReseñaModelo $modelo;
    private int $usuario_id;

    public function __construct(PDO $conexion) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['usuario_id'])) {
            header('Location: loginVista.php');
            exit;
        }

        $this->usuario_id = (int) $_SESSION['usuario_id'];
        $this->modelo     = new ReseñaModelo($conexion);
    }

    // ─────────────────────────────────────────────
    // MOSTRAR FORMULARIO
    // ─────────────────────────────────────────────
    public function mostrarFormulario(): array {
        $contrato_id = (int) ($_GET['contrato_id'] ?? 0);

        $check = $this->modelo->puedeReseñar($contrato_id, $this->usuario_id);

        return [
            'puede'       => $check['puede'],
            'motivo'      => $check['motivo'],
            'contrato'    => $this->modelo->getContratoConReseña($contrato_id),
            'contrato_id' => $contrato_id,
            'usuario_id'  => $this->usuario_id,
        ];
    }

    // ─────────────────────────────────────────────
    // CREAR RESEÑA (POST)
    // ─────────────────────────────────────────────
    public function crear(): void {
        $contrato_id = (int) ($_POST['contrato_id'] ?? 0);
        $puntuacion  = (int) ($_POST['puntuacion']  ?? 0);
        $comentario  = $_POST['comentario'] ?? null;

        if (!$contrato_id || $puntuacion < 1 || $puntuacion > 5) {
            header('Location: resenaVista.php?contrato_id=' . $contrato_id . '&error=datos');
            exit;
        }

        $id = $this->modelo->crearReseña(
            $contrato_id,
            $this->usuario_id,
            $puntuacion,
            $comentario
        );

        if ($id > 0) {
            header('Location: perfil.php?tab=contratados&msg=reseña_ok');
        } else {
            header('Location: resenaVista.php?contrato_id=' . $contrato_id . '&error=no_permitido');
        }
        exit;
    }
}
