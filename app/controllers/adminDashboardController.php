<?php
require_once '../models/usersModel.php';
require_once '../models/treesModel.php';
require_once '../models/speciesModel.php';
session_start();

class AdminDashboardController
{
    private $trees;
    private $users;
    private $species;
    private $userData = null;
    private $data;

    public function __construct()
    {
        if ($this->checkAuth()) {
            $this->initializeModels();
        }
    }

    private function initializeModels()
    {
        $this->users = new UsersModel();
        $this->trees = new TreesModel();
        $this->species = new SpeciesModel();
        $this->data = [];
    }

    public function renderDashboard()
    {
        // Verificamos de nuevo la autenticación por seguridad
        if (!$this->checkAuth()) {
            $this->redirectToLogin("Se requiere autenticación para acceder.");
            return;
        }

        $this->loadData();
        $data = $this->getData();
        include 'adminDashboard.php';
    }
    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirectToLogin("Debes iniciar sesión para acceder.");
        } else {
            if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
                $this->redirectToLogin("Error de sesión. Por favor, vuelve a iniciar sesión.");
                return false;
            }

            $this->userData = $_SESSION['user'];
            if (!isset($this->userData['role']) || $this->userData['role'] !== 'admin') {
                $this->redirectToLogin("No tienes permisos para acceder al panel de administración.");
                return false;
            }
            return true;
        }
        if (isset($_SESSION['user_id'])) {
            $loginId = $_SESSION['user_id'];
            $user = $userModel->getUserByLoginId($loginId);
            $roleId = $user['role_id'];
        } else {
            echo "No se ha encontrado login_id en la sesión.";
        }
    }

    private function redirectToLogin($message)
    {
        $_SESSION['error_message'] = $message;
        header('Location: http://mytrees.com/app/views/login.php');
        exit();
    }

    private function loadData()
    {
        if (empty($this->data)) {
            try {
                // Realizamos todas las consultas en un solo paso
                $this->data = [
                    'userName' => $this->userData['name'],
                    'countUsers' => $this->users->countFriends(),
                    'countAvailableTrees' => $this->trees->countAvailableTrees(),
                    'countSoldTrees' => $this->trees->countSoldTrees(),
                    'speciesList' => $this->species->getAllSpecies()
                ];
            } catch (Exception $e) {
                error_log("Error cargando datos: " . $e->getMessage());
                // Asignamos valores por defecto en caso de error
                $this->data = [
                    'userName' => $this->userData['name'],
                    'countUsers' => 0,
                    'treesStats' => ['available' => 0, 'sold' => 0],
                    'speciesList' => []
                ];
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function handlePostRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            switch ($action) {
                case 'logOut':
                    $this->logOut();
                    break;
                case 'update_species':
                    $this->updateSpecies($_POST['species_id'], $_POST['new_commercial_name'], $_POST['new_scientific_name']);
                    break;
                case 'delete_species':
                    $this->deleteSpecies($_POST['species_id']);
                    break;
            }
        }
    }

    private function updateSpecies($id, $commercialName, $scientificName)
    {
        try {
            // Actualizamos la especie
            $this->species->updateSpecies($id, $commercialName, $scientificName);
            $_SESSION['flash_message'] = "Especie actualizada con éxito.";
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Error al actualizar la especie.";
        }
        header("Location: adminDashboard.php");
        exit();
    }

    private function deleteSpecies($id)
    {
        try {
            // Eliminamos la especie
            $this->species->deleteSpecies($id);
            $_SESSION['flash_message'] = "Especie eliminada con éxito.";
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Error al eliminar la especie.";
        }
        header("Location: adminDashboard.php");
        exit();
    }

    private function logOut()
    {
        session_destroy();
        header("Location: http://mytrees.com");
        exit();
    }
}

// Crear instancia del controlador y manejar las peticiones POST
$dashboard = new AdminDashboardController();
$dashboard->handlePostRequest();
