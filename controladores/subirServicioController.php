<?php
// controladores/subirServicioController.php

require_once __DIR__ . '/../modelo/servicioModelo.php';
require_once __DIR__ . '/../modelo/imagenServicioModelo.php';

class SubirServicioController {

    private ServicioModelo $modelo;
    private ImagenServicioModelo $imagenes;
    private PDO $conexion;

    public function __construct(PDO $conexion) {
        $this->conexion = $conexion;
        $this->modelo   = new ServicioModelo($conexion);
        $this->imagenes = new ImagenServicioModelo($conexion);
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

        $servicio_id = $this->modelo->guardarServicio($datos);

        // Procesar imágenes (máximo 3) si se han subido
        if (!empty($_FILES['imagenes']['name'][0])) {
            $erroresImg = $this->imagenes->procesarImagenes($servicio_id, $_FILES);
            if (!empty($erroresImg)) {
                // El servicio sí se creó, pero hubo problemas con alguna imagen.
                // Lo informamos en la siguiente página vía query string.
                $msg = urlencode(implode(' | ', $erroresImg));
                header('Location: servicio.php?id=' . $servicio_id . '&img_warn=' . $msg);
                exit;
            }
        }

        header('Location: servicio.php?id=' . $servicio_id . '&creado=ok');
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