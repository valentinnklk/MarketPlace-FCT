<!-- bratu-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        .formulario { 
            border: 1px solid #ccc; 
            padding: 20px; 
            margin: 20px auto; 
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
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        h2 {
            color: #666;
            margin-top: 0;
            text-align: center;
        }
        .enlace {
            text-align: center;
            margin-top: 15px;
        }
        .enlace a {
            color: #4CAF50;
            text-decoration: none;
        }
        .enlace a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Registro de Usuario</h1>
    
    <?php if (isset($_GET['respuestaCreacionUsu'])): ?>
        <div class="mensaje-server <?php echo $_GET['creacion'] == 'exito' ? 'mensaje-exito' : 'mensaje-error'; ?>">
            <?php echo htmlspecialchars($_GET['respuestaCreacionUsu']); ?>
        </div>
    <?php endif; ?>

    <div class="formulario">
        <h2>Crear cuenta</h2>
        <form action="../CONTROLADORES/registroControlador.php" method="POST" id="formRegistro">
            <label>Nombre:</label><br>
            <input type="text" name="nombre" id="nombreRegistro" required>
            <div id="errorNombreRegistro" class="error-js"></div>
            <br>
            
            <label>Email:</label><br>
            <input type="email" name="email" id="emailRegistro" required>
            <div id="errorEmailRegistro" class="error-js"></div>
            <br>
            
            <label>Contraseña:</label><br>
            <input type="password" name="password" id="passwordRegistro" required>
            <div id="errorPasswordRegistro" class="error-js"></div>
            <br>
            
            <label>Ubicación:</label><br>
            <input type="text" name="ubicacion" id="ubicacionRegistro" required>
            <div id="errorUbicacionRegistro" class="error-js"></div>
            <br>
            
            <input type="submit" name="registroEnviar" value="Registrarse">
        </form>
        
        <div class="enlace">
            <a href="loginVista.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>

    <script src="../CONTROLADOR/validacionUsuarios.js"></script>
</body>
</html>