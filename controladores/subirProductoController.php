<?php
// controladores/subirProductoController.php
// Ruta del modelo: ../modelo/productoModelo.php

require_once __DIR__ . '/../modelo/productoModelo.php';

class SubirProductoController {

    private ProductoModelo $modelo;

    public function __construct(PDO $conexion) {
        $this->modelo = new ProductoModelo($conexion);
    }

    // Muestra el formulario vacío con categorías
    public function mostrarFormulario(): void {
        $categorias = $this->modelo->getCategorias();
        $errores    = [];

        // Vista en vista/subirProductoVista.php
        require __DIR__ . '/../vista/subirProductoVista.php';
    }

    // Procesa el formulario (POST)
    public function guardarProducto(int $idUsuario): void {
        $errores    = $this->validar($_POST);
        $categorias = $this->modelo->getCategorias();

        if (!empty($errores)) {
            // Hay errores: vuelve al formulario con los mensajes
            require __DIR__ . '/../vista/subirProductoVista.php';
            return;
        }

        $datos = [
            'vendedor_id'     => $idUsuario,
            'titulo'          => trim($_POST['titulo']),
            'descripcion'     => trim($_POST['descripcion']),
            'precio'          => (float) $_POST['precio'],
            'estado_producto' => $_POST['estado_producto'],
            'ubicacion'       => trim($_POST['ubicacion']),
        ];

        $idProducto  = $this->modelo->guardarProducto($datos);
        $idCategoria = (int) ($_POST['categoria_id'] ?? 0);

        if ($idCategoria > 0) {
            $this->modelo->guardarCategoria($idProducto, $idCategoria);
        }

        // Redirige a perfil.php (en vista/)
        header('Location: perfil.php');
        exit;
    }

    // Valida los campos obligatorios
    private function validar(array $post): array {
        $errores = [];

        if (empty($post['titulo']) || strlen(trim($post['titulo'])) < 3)
            $errores[] = 'El título debe tener al menos 3 caracteres.';

        if (empty($post['descripcion']))
            $errores[] = 'La descripción es obligatoria.';

        if (!isset($post['precio']) || $post['precio'] === '' || (float)$post['precio'] < 0)
            $errores[] = 'Introduce un precio válido.';

        if (empty($post['estado_producto']))
            $errores[] = 'Selecciona el estado del producto.';

        if (empty($post['ubicacion']))
            $errores[] = 'La ubicación es obligatoria.';

        return $errores;
    }
}
