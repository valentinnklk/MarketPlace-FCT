<?php
// controladores/perfilController.php
// Ruta del modelo: ../modelo/perfilModelo.php
// (desde controladores/ subimos un nivel con ../)

require_once __DIR__ . '/../modelo/perfilModelo.php';

class PerfilController {

    private PerfilModelo $modelo;

    public function __construct(PDO $conexion) {
        $this->modelo = new PerfilModelo($conexion);
    }

    // Carga todos los datos y llama a la vista
    public function mostrarPerfil(int $idUsuario): void {
        $usuario   = $this->modelo->getUsuario($idUsuario);
        $enVenta   = $this->modelo->getProductosEnVenta($idUsuario);
        $compras   = $this->modelo->getCompras($idUsuario);
        $favoritos = $this->modelo->getFavoritos($idUsuario);
        $resenas   = $this->modelo->getResenas($idUsuario);

        // La vista está en vista/perfil.php
        // Desde controladores/ → ../vista/perfilVista.php
        require __DIR__ . '/../vista/perfilVista.php';
    }

    // Elimina un favorito (POST desde la vista)
    public function eliminarFavorito(int $idUsuario): void {
        $idProducto = (int) ($_POST['producto_id'] ?? 0);

        if ($idProducto > 0) {
            $this->modelo->eliminarFavorito($idUsuario, $idProducto);
        }

        // Redirige a perfil.php (que está en vista/)
        header('Location: perfil.php?tab=favoritos');
        exit;
    }
}
