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

    public function handlePostActions() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'view_species':
                    if (isset($_POST['species_id'])) {
                        $this->toggleSpeciesVisibility($_POST['species_id']);
                        // Redirigir a la misma página para refrescar
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    }
                    break;
            }
        }
    }
    
    private function toggleSpeciesVisibility($speciesId) {
        if (!isset($_SESSION['visible_species'])) {
            $_SESSION['visible_species'] = [];
        }
        
        // Asegúrate de que $speciesId sea un número
        $speciesId = (int)$speciesId;
        
        if (in_array($speciesId, $_SESSION['visible_species'])) {
            $_SESSION['visible_species'] = array_values(array_diff($_SESSION['visible_species'], [$speciesId]));
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

}