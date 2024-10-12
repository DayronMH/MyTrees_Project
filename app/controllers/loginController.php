<?php
session_start();
require_once '../models/loginModel.php';
class loginController {
    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['action'] == 'login') {
                self::handleLogin();
            } if ($_POST['action'] == 'register') {
                self::routeRegister();
                        
            }
        }
    }
    
    public static function handleLogin() {
        $userModel = new loginModel('login');
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedInputPassword = md5($password);
        $user = $userModel->getUserByUsername($username);
        if ($user) {
            if ($hashedInputPassword === $user['password']) {
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
    }
    public static function routeRegister(){
        header('Location:http://userpractice.com/app/views/register.php');
        exit();
    }
    
}

$authController = new loginController(); 
