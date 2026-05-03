<?php
Require_once "../conexion.php";

class Usuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
    public function mostrarUsuario(){
        $sql= "SELECT nombre FROM usuarios";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function comprobarUsuario($email, $contrasena){
        $sql = "SELECT * FROM usuarios WHERE email = :email AND contrseña_hash = :contrasena";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerPorId($id) {
    $sql = "SELECT id, nombre, email, ubicacion, avatar_url, es_administrador, fecha_registro, tiempo_respuesta
            FROM usuarios WHERE id = :id";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function obtenerServiciosPorUsuario($id) {
    $sql = "SELECT s.id, s.titulo, s.descripcion, s.precio, s.unidad_cobro
            FROM servicios s
            WHERE s.prestador_id = :id AND s.activo = 1";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>