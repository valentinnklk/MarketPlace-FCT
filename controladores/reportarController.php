<?php
session_start();
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../MODELO/reportesModelo.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../VISTA/loginVista.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportador_id       = $_SESSION['usuario_id'];
    $tipo                = $_POST['tipo'];         // 'servicio' o 'usuario'
    $servicio_id         = $_POST['servicio_id'] ?: null;
    $usuario_reportado_id = $_POST['usuario_reportado_id'] ?: null;
    $motivo              = trim($_POST['motivo']);
    $redirect            = $_POST['redirect'];     // para volver a la página correcta

    if (empty($motivo)) {
        header("Location: $redirect&reporte=error");
        exit();
    }

    insertarReporte($reportador_id, $tipo, $servicio_id, $usuario_reportado_id, $motivo);
    header("Location: $redirect&reporte=ok");
    exit();
}
?>