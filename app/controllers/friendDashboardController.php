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
            $this->purchasedTrees = $this->getUserPurchasedTrees($this->user);
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

    public function getUserPurchasedTrees(int $userId): array {
        $purchasedTrees = $this->treeModel->getPurchasedTreesByUser($userId);
        $trees = [];
        
        foreach ($purchasedTrees as $tree) {
            $trees[] = [
                'height' => $tree['height'],
                'location' => htmlspecialchars($tree['location']),
                'price' => number_format($tree['price'], 2),
                'photo_url' => $tree['photo_url'],
                'species' => htmlspecialchars($tree['commercial_name'])
            ];
        }
        return $trees;
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
}