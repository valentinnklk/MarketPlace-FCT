<?php
require_once __DIR__ . '/../conexion.php';
//bratu

// Verifica si un email ya existe
function emailExiste($email) {
    global $conexion;
    
    $sql = "SELECT id FROM usuarios WHERE email = :email";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $resultado = $stmt->fetch();
    if ($resultado) {
        return true;
    } else {
        return false;
    }
}

// Verifica si un nombre ya existe
function nombreExiste($nombre) {
    global $conexion;
    
    $sql = "SELECT id FROM usuarios WHERE nombre = :nombre";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();
    
    $resultado = $stmt->fetch();
    if ($resultado) {
        return true;
    } else {
        return false;
    }
}

// Inserta un usuario (normal o admin según el parámetro)
function insertarUsuario($nombre, $email, $password, $ubicacion, $esAdmin) {
    global $conexion;
    
    if (emailExiste($email)) {
        return false;
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuarios (nombre, email, contraseña_hash, ubicacion, es_administrador, fecha_registro) 
            VALUES (:nombre, :email, :hash, :ubicacion, :esAdmin, NOW())";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':hash', $hash);
    $stmt->bindParam(':ubicacion', $ubicacion);
    $stmt->bindParam(':esAdmin', $esAdmin, PDO::PARAM_BOOL);
    
    return $stmt->execute();
}

// Obtiene un usuario por su email
function obtenerUsuarioPorEmail($email) {
    global $conexion;
    
    $sql = "SELECT id, nombre, email, contraseña_hash, es_administrador, ubicacion 
            FROM usuarios WHERE email = :email";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>