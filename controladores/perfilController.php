<?php
// controladores/perfilController.php
// Ruta del modelo: ../modelo/perfilModelo.php
 
require_once __DIR__ . '/../modelo/perfilModelo.php';
 
class PerfilController {
 
    private PerfilModelo $modelo;
 
    public function __construct(PDO $conexion) {
        $this->modelo = new PerfilModelo($conexion);
    }
 
    // Carga todos los datos y llama a la vista
    public function mostrarPerfil(int $idUsuario): void {
        $usuario            = $this->modelo->getUsuario($idUsuario);
        $serviciosOfrecidos = $this->modelo->getServiciosOfrecidos($idUsuario);
        $contratosCliente   = $this->modelo->getContratosComoCliente($idUsuario);
        $contratosPrestador = $this->modelo->getContratosComoPrestador($idUsuario);
        $favoritos          = $this->modelo->getFavoritos($idUsuario);
        $valoraciones       = $this->modelo->getValoracionesRecibidas($idUsuario);
        $valoracionMedia    = $this->modelo->getValoracionMedia($idUsuario);
 
        require __DIR__ . '/../vista/perfilVista.php';
    }
 
    // Elimina un favorito (POST desde la vista)
    public function eliminarFavorito(int $idUsuario): void {
        $idServicio = (int) ($_POST['servicio_id'] ?? 0);
 
        if ($idServicio > 0) {
            $this->modelo->eliminarFavorito($idUsuario, $idServicio);
        }
 
        header('Location: perfil.php?tab=favoritos');
        exit;
    }
}
