<?php
require_once '../models/usersModel.php';
require_once '../models/treesModel.php';
require_once '../models/speciesModel.php';

class AdminDashboardController
{
    private $userModel;
    private $treeModel;
    private $speciesModel;
    private $user;
    private $roleId;

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