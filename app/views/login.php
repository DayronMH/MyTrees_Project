<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tienda de Árboles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e8f5e9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        h1 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            margin-bottom: 1rem;
            padding: 0.5rem;
            border: 2px solid #81c784;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        input:hover {
            border-color: #2e7d32;
        }
        input:focus {
            outline: none;
            border-color: #2e7d32;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
        }
        button {
            background-color: #2e7d32;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        button:hover {
            background-color: #1b5e20;
        }
        button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.5);
        }
        button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        button:active::after {
            animation: ripple 0.6s ease-out;
        }
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }
            20% {
                transform: scale(25, 25);
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }
        .forgot-password {
            text-align: center;
            margin-top: 1rem;
        }
        .forgot-password a {
            color: #c62828;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        .forgot-password a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #c62828;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .forgot-password a:hover {
            color: #b71c1c;
        }
        .forgot-password a:hover::after {
            transform: scaleX(1);
        }
        .forgot-password a:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.3);
            border-radius: 2px;
        }
        .tree-icon {
            text-align: center;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }
        .tree-icon:hover {
            transform: scale(1.1);
        }
        .tree-circle {
            width: 100px;
            height: 100px;
            background-color: #f5f5f5;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            border: 3px solid #2e7d32;
        }
        .tree-circle span {
            font-size: 4rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="tree-icon">
            <div class="tree-circle">
                <span>🌳</span>
            </div>
        </div>
        <h1>Bienvenido</h1>
        <form>
            <input type="email" placeholder="Correo electrónico" required>
            <input type="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar sesión</button>
        </form>
        <div class="forgot-password">
            <a href="#">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</body>
</html>
