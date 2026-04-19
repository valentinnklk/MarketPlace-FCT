<!-- bratu-->
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
        input[type="email"], input[type="password"] {
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
    <h1>Iniciar Sesión</h1>
    
    <?php if (isset($_GET['server_msg'])): ?>
        <div class="mensaje-server <?php echo $_GET['creacion'] == 'exito' ? 'mensaje-exito' : 'mensaje-error'; ?>">
            <?php echo htmlspecialchars(urldecode($_GET['server_msg'])); ?>
        </div>
    <?php endif; ?>

    <div class="formulario">
        <h2>Acceder a tu cuenta</h2>
        <form action="../CONTROLADORES/loginControlador.php" method="POST" id="formLogin">
            <label>Email:</label><br>
            <input type="email" name="email" id="emailLogin" required>
            <div id="errorEmailLogin" class="error-js"></div>
            <br>
            
            <label>Contraseña:</label><br>
            <input type="password" name="password" id="passwordLogin" required>
            <div id="errorPasswordLogin" class="error-js"></div>
            <br>
            
            <input type="submit" name="loginEnviar" value="Iniciar Sesión">
        </form>
        
        <div class="enlace">
            <a href="registroVista.php">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>

    <script src="../controladores/validacionUsuarios.js"></script>
</body>
</html>