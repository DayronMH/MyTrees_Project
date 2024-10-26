<?php
require_once '../models/usersModel.php';
require_once '../models/treesModel.php';
require_once '../models/speciesModel.php';

class AdminDashboardController {
    private $userModel;
    private $treeModel;
    private $speciesModel;
    private $user;
    private $roleId;

    public $friendsCount, $availableTreesCount, $soldTreesCount;

    public function __construct() {
        // Iniciar sesión si no está iniciada
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UsersModel();
        $this->treeModel = new TreesModel();
        $this->speciesModel = new SpeciesModel();

        $this->checkAuth();
        $this->fetch_stats();
        $this->speciesCRUD();
        $this->handlePostActions();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: http://mytrees.com");
            exit();
        }
        $this->user = $this->userModel->getUserById($_SESSION['user_id']);
        $this->roleId = $this->user['role'];

        if ($this->roleId !== 'admin') {
            exit();
        }
        $_SESSION['username'] = $this->user['name'];
    }

    private function handlePostActions() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'view_species':
                        $this->viewSpecies($_POST['species_id']);
                        break;
                    case 'edit_species':
                        $this->editSpecies($_POST['species_id']);
                        break;
                    case 'delete_species':
                        $this->deleteSpecies($_POST['species_id']);
                        break;
                }
            }
        }
    }

    private function viewSpecies($speciesId) {
        if (!isset($_SESSION['visible_species'])) {
            $_SESSION['visible_species'] = [];
        }

        if (in_array($speciesId, $_SESSION['visible_species'])) {
            $_SESSION['visible_species'] = array_diff($_SESSION['visible_species'], [$speciesId]);
        } else {
            $_SESSION['visible_species'][] = $speciesId;
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    private function editSpecies($speciesId) {
        $species = $this->speciesModel->getSpeciesById($speciesId);
        if ($species) {
            $_SESSION['editing_species'] = $speciesId;
            $_SESSION['species_data'] = $species;
            header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=' . $speciesId);
        } else {
            $_SESSION['error'] = "Especie no encontrada";
            header('Location: ' . $_SERVER['PHP_SELF']);
        }
        exit();
    }

    private function deleteSpecies($speciesId) {
        if ($this->speciesModel->deleteSpecies($speciesId)) {
            $_SESSION['success'] = "Especie eliminada correctamente";
            $this->speciesCRUD(); // Actualizar las listas
        } else {
            $_SESSION['error'] = "Error al eliminar la especie";
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    public function fetch_stats() {
        $_SESSION['friendsCount'] = $this->userModel->countFriends();
        $_SESSION['availableTreesCount'] = $this->treeModel->countAvailableTrees();
        $_SESSION['soldTreesCount'] = $this->treeModel->countSoldTrees();
        require_once '../views/adminDashboard.php';
    }

    public function speciesCRUD() {
        $_SESSION['commercial_names'] = $this->speciesModel->getCommercialNames();
        $_SESSION['scientific_names'] = $this->speciesModel->getScientificNames();
        require_once '../views/adminDashboard.php';
    }

    private function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}



