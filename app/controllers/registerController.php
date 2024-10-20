<?php
require_once '../models/registerModel.php';
session_start();

class Register {
    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['action'] == 'save') {
                $this->addUser();
            }
        }
    }

    public function addUser() {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $province_id = $this->getProvince();

        if (empty($username)) $mistakes[] = 'El nombre de usuario no puede estar vacío.';
        if (empty($password) || strlen($password) < 8) $mistakes[] = 'La contraseña debe tener más de 8 caracteres.';
        if (empty($name)) $mistakes[] = 'El nombre no puede estar vacío.';
        if (empty($lastname)) $mistakes[] = 'El apellido no puede estar vacío.';
        if (empty($phone) || !preg_match('/^[0-9]{8}$/', $phone)) $mistakes[] = 'El número de teléfono debe tener 8 dígitos.';
        if (empty($address)) $mistakes[] = 'La dirección no puede estar vacía.';
        if (empty($province_id)) $mistakes[] = 'Debe seleccionar una provincia válida.';
        if (empty($mistakes)) {
            $loginModel = new loginModel('login');
            $registerModel = new RegisterModel('users');
            $hashedPassword = md5($password); 
            $totalUsers = $registerModel->getAllUsers(); // Obtén todos los usuarios
            $role_id = (count($totalUsers) == 0) ? 1 : 2;
            $_SESSION['role_id'] = $role_id;
            $loginId = $loginModel->createLogin($username, $hashedPassword);

            if ($loginId) {
                echo '<div class="alert alert-success">Login registrado correctamente.</div>';
                 
                $registerModel->createUser($name,$lastname,$phone,$address,$loginId,$province_id,$role_id);
                echo '<div class="alert alert-success">Usuario registrado correctamente.</div>';
                header('Location: http://userpractice.com/');
                exit();
            } else {
                $mistakes[] = 'Error al registrar el login.';
            }
        }
        if (!empty($mistakes)) {
            foreach ($mistakes as $error) {
                echo "<script>alert('Error: {$error}');
                    window.location.href = 'http://userpractice.com/app/views/register.php';    
               </script>";
            }
            exit();
        }
    }

    private function getProvince() {
        $province_id = filter_input(INPUT_POST, 'province', FILTER_VALIDATE_INT);

        if ($province_id && $province_id >= 1 && $province_id <= 7) {
            return $province_id; 
        } else {
            return null; 
        }
    }
}

$regis = new Register();
