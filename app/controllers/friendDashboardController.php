<?php
require_once '../models/usersModel.php';
require_once '../models/treesModel.php';
require_once '../models/speciesModel.php';
require_once '../models/salesModel.php';

class FriendDashboardController {
    private $userModel;
    private $treeModel;
    private $salesModel;
    private $speciesModel;
    private $user;
    public $purchasedTrees;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Inicialización de modelos
        $this->userModel = new UsersModel();
        $this->treeModel = new TreesModel();
        $this->speciesModel = new SpeciesModel();
        $this->salesModel = new SalesModel();

        // Obtener usuario de sesión
        if (isset($_SESSION['user_id'])) {
            $this->user = (int)$_SESSION['user_id'];
            $this->purchasedTrees = $this->getTreesByOwnerId($this->user);
        } else {
            $this->user = null;
            $this->purchasedTrees = [];
        }

        // Procesar acciones de POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'buy-tree':
                    header('location: http://mytrees.com');
                    break;
            }
        }
    }

    public function getAvailableTrees() {
        return $this->treeModel->getAvailableTreesWithSpecies();
    }

    public function getTreesByOwnerId(int $userId) {
       return $this->treeModel->getPurchasedTreesByUser($userId);
    }

    public function buyTree($userId, $treeId) {
        if ($this->salesModel->isTreeAvailable($treeId)) {
            if ($this->salesModel->createSale($userId, $treeId)) {
                $this->salesModel->markTreeAsSold($treeId);
                return true;
            }
        }
        return false;
    }

    public function getUserById($UserId) {

        return $this->userModel->getUserById($UserId);
    }
}