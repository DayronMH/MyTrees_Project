<?php
require_once '../models/usersModel.php';

class RegisterController
{
    public function __construct()
    {
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
    public function registerUser()
    {
        // Sanitiza las entradas
        $name = htmlspecialchars(filter_input(INPUT_POST, 'name', FILTER_DEFAULT));
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(filter_input(INPUT_POST, 'password', FILTER_DEFAULT));
        $phone = htmlspecialchars(filter_input(INPUT_POST, 'phone', FILTER_DEFAULT));
        $address = htmlspecialchars(filter_input(INPUT_POST, 'address', FILTER_DEFAULT));
        $country = htmlspecialchars(filter_input(INPUT_POST, 'country', FILTER_DEFAULT));
        // Assuming this is a dropdown select field

        // Input validation (you can add more validation rules as needed)
        if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address) || empty($country)) {
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

        
        $isCreated = $userModel->createFriendUser($name, $email, $password, $phone, $address, $country);

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

    public function redirectToLogin()
    {
        header('Location: http://mytrees.com/app/views/login.php');
        exit();
    }
}

$registerController = new RegisterController();
