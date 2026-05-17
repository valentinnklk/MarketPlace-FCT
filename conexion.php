<?php
/*
try{
    $conexion = new PDO("mysql:host=localhost;dbname=marketplace", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

ESTA ES LA CONEXIÓN PARA LA BASE DE DATOS EN AWS RDS, RECUERDA CAMBIAR LOS DATOS DE CONEXIÓN SI LO VAS A PROBAR EN LOCALHOST
*/
try {
    $conexion = new PDO(
        "mysql:host=database-1.ctpnfcuw4k1b.us-east-1.rds.amazonaws.com;dbname=marketplace_bmv",
        "admin",
        "TFG20252026"
    );

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo " Error de conexión: " . $e->getMessage();
}

?>

