<?php
// controladores/subirServicioController.php

require_once __DIR__ . '/../modelo/servicioModelo.php';

class SubirServicioController {

    private ServicioModelo $modelo;

    public function __construct(PDO $conexion) {
        $this->modelo = new ServicioModelo($conexion);
    }

    // Muestra el formulario vacío con categorías
    public function mostrarFormulario(): void {
        $categorias = $this->modelo->getCategorias();
        $errores    = [];

        require __DIR__ . '/../vista/subirServicioVista.php';
    }

    // Procesa el formulario (POST)
    public function guardarServicio(int $idUsuario): void {
        $errores    = $this->validar($_POST);
        $categorias = $this->modelo->getCategorias();

        if (!empty($errores)) {
            require __DIR__ . '/../vista/subirServicioVista.php';
            return;
        }

        $datos = [
            'prestador_id'      => $idUsuario,
            'categoria_id'      => (int) ($_POST['categoria_id'] ?? 0),
            'titulo'            => trim($_POST['titulo']),
            'descripcion'       => trim($_POST['descripcion']),
            'precio'            => (float) $_POST['precio'],
            'unidad_cobro'      => $_POST['unidad_cobro'],
            'duracion_estimada' => trim($_POST['duracion_estimada'] ?? ''),
            'ubicacion'         => trim($_POST['ubicacion']),
        ];

        $this->modelo->guardarServicio($datos);

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

        if (empty($post['unidad_cobro']))
            $errores[] = 'Selecciona la unidad de cobro.';

        if (empty($post['ubicacion']))
            $errores[] = 'La ubicación es obligatoria.';

        if (empty($post['categoria_id']) || (int)$post['categoria_id'] === 0)
            $errores[] = 'Selecciona una categoría.';

        return $errores;
    }
}