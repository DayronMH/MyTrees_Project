<?php
session_start();
require_once '../models/usersModel.php';
class loginController {
    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['action'] == 'login') {
                self::authLogin();
            } if ($_POST['action'] == 'register') {
                self::routeRegister();
                        
            }
        }
    }
    
    public static function authLogin() {
        $userModel = new UsersModel();
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Intentar recuperar el usuario por el correo electrónico
        $user = $userModel->handleLogin($email, $password);
    
        if ($user) {
            // Verificar la contraseña con la que se recuperó el usuario
            if (password_verify($password, $user['password'])) {
                // Iniciar sesión
                $_SESSION['user_id'] = $user['id'];
                echo "<script>
                        alert('Login exitoso');
                        window.location.href = 'http://userpractice.com/app/views/table.php';
                      </script>";
                exit();
            } else {
                echo "<script>
                        alert('Contraseña incorrecta');
                        window.location.href = 'http://userpractice.com/app/views/login.php';
                      </script>";
                exit(); 
            }
        } else {
            echo "<script>
                    alert('Usuario no encontrado');
                    window.location.href = 'http://userpractice.com/app/views/login.php';
                  </script>";
            exit(); 
        }
    }    public static function routeRegister(){
        header('Location:http://userpractice.com/app/views/register.php');
        exit();
    }
    
}

$authController = new loginController(); 
