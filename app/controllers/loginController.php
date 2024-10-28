<?php
require_once '../models/usersModel.php';

class loginController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['action'] == 'login') {
                self::authLogin();
            } else if ($_POST['action'] == 'register') {
                self::routeRegister();
            }
        }
    }
    
    public static function authLogin() {
        $userModel = new UsersModel();
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $userModel->handleLogin($email);
        
        if (empty($email) || empty($password)) {
            echo "<script>
                    alert('Debes llenar los campos de email y contraseña');
                    window.location.href = 'http://mytrees.com/app/views/login.php';
                  </script>";
            exit();
        }

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                if ($user['role'] === 'admin') {
                    echo "<script>
                        alert('Login exitoso');
                        window.location.href = 'http://mytrees.com/app/views/adminDashboard.php';
                      </script>";
                    exit();
                } else if ($user['role'] === 'friend') {
                    echo "<script>
                        alert('Login exitoso');
                        window.location.href = 'http://mytrees.com/app/views/friendDashboard.php';
                      </script>";
                    exit();
                }
            } else {
                echo "<script>
                        alert('Contraseña incorrecta');
                        window.location.href = 'http://mytrees.com/app/views/login.php';
                      </script>";
                exit(); 
            }
        } else {
            echo "<script>
                    alert('Usuario no encontrado');
                    window.location.href = 'http://mytrees.com/app/views/login.php';
                  </script>";
            exit(); 
        }
    }

    public static function routeRegister() {
        header('Location: http://mytrees.com/app/views/register.php');
        exit();
    }
}

$authController = new loginController(); 