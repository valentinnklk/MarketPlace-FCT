<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <style>
        .formulario { 
            border: 1px solid #ccc; 
            padding: 20px; 
            margin: 20px; 
            width: 400px; 
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .error-js { 
            color: #ff6b6b; 
            font-size: 0.85em; 
            margin-top: 2px; 
            min-height: 20px; 
        }
        .campo-error { 
            border: 2px solid #ff6b6b !important; 
        }
        .mensaje-server { 
            margin: 10px 0; 
            padding: 10px; 
            border-radius: 5px; 
        }
        .mensaje-exito { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .mensaje-error { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        h1 {
            color: #333;
            margin-left: 20px;
            display: inline-block;
        }
        .btn-home {
            display: inline-block;
            margin-left: 20px;
            padding: 8px 15px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn-home:hover {
            background-color: #5a6268;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        h2 {
            color: #666;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <a href="home.php" class="btn-home"> Volver al Home</a>
    <div class="header">
        <div class="header-left">
            <h1>PANEL DE ADMINISTRACIÓN</h1>
        </div>
    </div>
    
    <!-- Mostrar mensajes del servidor -->
    <?php if (isset($_GET['respuestaCreacionUsu'])): ?>
        <div class="mensaje-server <?php echo $_GET['creacion'] == 'exito' ? 'mensaje-exito' : 'mensaje-error'; ?>">
            <?php echo htmlspecialchars($_GET['respuestaCreacionUsu']); ?>
        </div>
    <?php endif; ?>

    <!-- FORMULARIO USUARIO NORMAL -->
    <div class="formulario">
        <h2>Crear Usuario Normal</h2>
        <form action="../CONTROLADORES/panelAdministracionControlador.php" method="POST" id="formNormal">
            <input type="hidden" name="tipo" value="normal">
            
            <label>Nombre:</label><br>
            <input type="text" name="nombre" id="nombreNormal" required>
            <div id="errorNombreNormal" class="error-js"></div>
            <br>
            
            <label>Email:</label><br>
            <input type="email" name="email" id="emailNormal" required>
            <div id="errorEmailNormal" class="error-js"></div>
            <br>
            
            <label>Contraseña:</label><br>
            <input type="password" name="password" id="passwordNormal" required>
            <div id="errorPasswordNormal" class="error-js"></div>
            <br>
            
            <label>Ubicación:</label><br>
            <input type="text" name="ubicacion" id="ubicacionNormal" required>
            <div id="errorUbicacionNormal" class="error-js"></div>
            <br>
            
            <input type="submit" name="usuarioNormal" value="Crear Usuario Normal">
        </form>
    </div>

    <!-- FORMULARIO ADMINISTRADOR -->
    <div class="formulario">
        <h2>Crear Administrador</h2>
        <form action="../CONTROLADORES/panelAdministracionControlador.php" method="POST" id="formAdmin">
            <input type="hidden" name="tipo" value="admin">
            
            <label>Nombre:</label><br>
            <input type="text" name="nombre" id="nombreAdmin" required>
            <div id="errorNombreAdmin" class="error-js"></div>
            <br>
            
            <label>Email:</label><br>
            <input type="email" name="email" id="emailAdmin" required>
            <div id="errorEmailAdmin" class="error-js"></div>
            <br>
            
            <label>Contraseña:</label><br>
            <input type="password" name="password" id="passwordAdmin" required>
            <div id="errorPasswordAdmin" class="error-js"></div>
            <br>
            
            <label>Ubicación:</label><br>
            <input type="text" name="ubicacion" id="ubicacionAdmin" required>
            <div id="errorUbicacionAdmin" class="error-js"></div>
            <br>
            
            <input type="submit" name="usuarioAdmin" value="Crear Administrador">
        </form>
    </div>

    <script src="../controladores/validacionUsuarios.js"></script>
</body>
</html>