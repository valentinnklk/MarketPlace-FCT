<?php
try{
    $conexion = new PDO("mysql:host=localhost;dbname=marketplace", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>