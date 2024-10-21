<?php
require_once '../models/usersModel.php';

class RegisterController {
    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['action'] === 'register') {
                $this->registerUser();
            } elseif ($_POST['action'] === 'login') {
                $this->redirectToLogin();
            }
        }
    }

    /**
     * Register a new user.
     */
    public function registerUser() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';

        // Input validation (you can add more validation rules as needed)
        if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address)) {
            echo "<script>
                    alert('Todos los campos son obligatorios.');
                    window.history.back();
                  </script>";
            exit();
        }

        // Use UsersModel to create a new user
        $userModel = new UsersModel();

        // Check if the user already exists
        $existingUser = $userModel->handleLogin($email);  // Assuming this method returns user data if found
        if ($existingUser) {
            echo "<script>
                    alert('El usuario con este correo ya existe.');
                    window.history.back();
                  </script>";
            exit();
        }

        // Register the new user
        $isCreated = $userModel->createUser($name, $email, $password, 'friend', $phone, $address, 'Costa Rica');  // Change role and country as needed

        if ($isCreated) {
            echo "<script>
                    alert('Registro exitoso. Ahora puedes iniciar sesión.');
                    window.location.href = 'http://mytrees.com/app/views/login.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Ocurrió un error al registrar el usuario. Por favor, inténtalo de nuevo.');
                    window.history.back();
                  </script>";
        }
    }

  
    public function redirectToLogin() {
        header('Location: http://mytrees.com/app/views/login.php');
        exit();
    }
}
$registerController = new RegisterController();
