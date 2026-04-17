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
}
?>