<?php
require_once '../models/usersModel.php';
require_once '../views/targetPage.php';
require_once '../../scripts/script.php'; 


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
            setTargetMessage('error', 'Debe llenar los campos de correo y contraseña');
            header('Location: http://mytrees.com/app/views/login.php');
            exit();
        }
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];

                if ($user['role'] === 'admin') {
                    setTargetMessage('success', 'Bienvenido, Administrador');
                    header('Location: http://mytrees.com/app/views/adminDashboard.php');
                    exit();
                } else if ($user['role'] === 'friend') {
                    setTargetMessage('success', 'Login exitoso');
                    header('Location: http://mytrees.com/app/views/friendDashboard.php');
                    exit();
                }
                else {
                    setTargetMessage('error', 'Rol no válido');
                    header('Location: http://mytrees.com/app/views/login.php');
                    exit();
                }
            } else {
                setTargetMessage('error', 'Contraseña incorrecta');
                header('Location: http://mytrees.com/app/views/login.php');
                exit(); 
            }
        } else {
            setTargetMessage('error', 'Usuario no encontrado');
            header('Location: http://mytrees.com/app/views/login.php');
            exit(); 
        }
    }
    public static function routeRegister() {
        header('Location: http://mytrees.com/app/views/register.php');
        exit();
    }
}

$authController = new loginController();