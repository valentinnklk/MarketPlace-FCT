<?php
require_once "../modelo/servicioModelo.php";

class ServicioController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Este es el que pide home.php en la línea 9
    public function obtenerTodos() {
        $servicio = new ServicioModelo($this->conexion);
        return $servicio->getServicios();
    }

    // Este lo usa subirServicio.php
    public function getCategorias() {
        $modelo = new ServicioModelo($this->conexion);
        return $modelo->getCategorias();
    }

    // Este procesa el guardado
    public function guardarServicio($datos) {
        $modelo = new ServicioModelo($this->conexion);
        return $modelo->guardarServicio($datos);
    }
    //buscador de servicios
    //buscador de servicios
    public function buscar($texto) {
        $modelo = new ServicioModelo($this->conexion);
        if ($texto !== '') {
            return $modelo->buscarServicios($texto);
        } else {
            return $modelo->getServicios();
        }
    }
    public function mostrarServicioPorId(int $id) {
        $modelo = new ServicioModelo($this->conexion);
        return $modelo->mostrarServicioPorId($id);
    }
}