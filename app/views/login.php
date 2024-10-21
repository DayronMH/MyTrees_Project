<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MyTrees</title>
    <link rel="stylesheet" href="http://miproyecto.local.com/MyTrees_Project/public/login.css">
</head>
<body>
    
    <div class="leaf"><span>🍂</span></div>
    <div class="leaf"><span>🍁</span></div>
    <div class="leaf"><span>🍂</span></div>
    <div class="leaf"><span>🍁</span></div>
    <div class="leaf"><span>🍂</span></div>
    <div class="leaf"><span>🍁</span></div>
    <div class="leaf"><span>🍂</span></div>
    <div class="leaf"><span>🍁</span></div>
    <div class="leaf"><span>🍂</span></div>
    <div class="leaf"><span>🍁</span></div>

    <div class="login-container">
        <div class="left-section">
            <div class="tree-icon">
                <div class="tree-circle">
                    <span>🌳</span>
                </div>
            </div>
            <h1>MyTrees</h1>
        </div>
        <div class="right-section">
            <h1>Bienvenido</h1>
            <form method="POST" action="http://mytrees.com/app/controllers/loginController.php">
<<<<<<< HEAD
                <input type="text" name="email" placeholder="Usuario" required>
=======
                <input type="text" name="username" placeholder="Usuario" required>
>>>>>>> origin/morera
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="action" value="login">Iniciar sesión</button>
                <p>¿No tienes usuario?</p>
                <button type="submit" name="action" value="register" class="register-button">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>