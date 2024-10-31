<?php
require_once '../models/usersModel.php';
require_once '../models/treesModel.php';
require_once '../models/speciesModel.php';

class AdminDashboardController
{
    private $userModel;
    private $treeModel;
    private $speciesModel;

    public $friendsCount, $availableTreesCount, $soldTreesCount;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UsersModel();
        $this->treeModel = new TreesModel();
        $this->speciesModel = new SpeciesModel();

        $this->checkAuth();
        $this->fetch_stats();
        $this->speciesCRUD();
        $this->getFriends();
        $this->handlePostActions();
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: http://mytrees.com");
            exit();
        }
        
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $_SESSION['username'] = $user['name'];
    }

    private function handlePostActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            switch ($_POST['action']) {
            
                case 'view_species':
                    if (isset($_POST['species_id'])) {
                        $this->toggleSpeciesVisibility($_POST['species_id']);
                    }
                    break;
    
                case 'edit_species':
                    if (isset($_POST['species_id'])) {
                        $speciesId = $_POST['species_id'];
                        header("Location: ../views/edit.php?id=$speciesId");
                        exit();
                    }
                    break;
            }
        }
    }
        private function toggleSpeciesVisibility($speciesId)
    {
        if (!isset($_SESSION['visible_species'])) {
            $_SESSION['visible_species'] = array();
        }

        if (in_array($speciesId, $_SESSION['visible_species'])) {
            $_SESSION['visible_species'] = array_diff($_SESSION['visible_species'], array($speciesId));
        } else {
            $_SESSION['visible_species'][] = $speciesId;
        }
    }


    public function getFriends()
    {
        $_SESSION['friends'] = $this->userModel->getFriends();
    }

    public function fetch_stats()
    {
        $_SESSION['friendsCount'] = $this->userModel->countFriends();
        $_SESSION['availableTreesCount'] = $this->treeModel->countAvailableTrees();
        $_SESSION['soldTreesCount'] = $this->treeModel->countSoldTrees();
    }

    public function speciesCRUD()
    {
        $_SESSION['commercial_names'] = $this->speciesModel->getCommercialNames();
        $_SESSION['scientific_names'] = $this->speciesModel->getScientificNames();
    }
    public function createSpecie($speciesId,$commercial_name, $scientific_name) {
        try {
            // Validar datos de entrada
            if (empty($commercial_name) || empty($scientific_name)) {
                return ['error' => 'Los nombres comercial y científico son requeridos.'];
            }

            // Verificar si la especie ya existe
            if ($this->speciesModel->hasTreesAssociated($speciesId)) {
                return ['error' => 'La especie ya existe en la base de datos.'];
            }

            // Crear la especie
            if ($this->speciesModel->createSpecie($commercial_name, $scientific_name)) {
                $_SESSION['message'] = 'Especie creada correctamente';
                header('Location: ../views/admindashboard.php');
                exit();
            } else {
                return ['error' => 'Error al crear la especie'];
            }
        } catch (Exception $e) {
            return ['error' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }
}