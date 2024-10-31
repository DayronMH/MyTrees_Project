<?php
require_once '../models/usersModel.php';
require_once '../models/treesModel.php';
require_once '../models/speciesModel.php';

class FriendDashboardController {
    private $userModel;
    private $treeModel;
    private $speciesModel;
    private $user;
    private $roleId;
    
    public $friendsCount, $availableTreesCount, $soldTreesCount;
    public $purchasedTrees;
    
    public function __construct() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->userModel = new UsersModel();
        $this->treeModel = new TreesModel();
        $this->speciesModel = new SpeciesModel();
        
        // Comprobar si el ID del usuario está en la sesión
        if (isset($_SESSION['user_id'])) {
            $this->user = (int)$_SESSION['user_id']; // Asegúrate de que sea un entero
            $this->purchasedTrees = $this->getUserPurchasedTrees($this->user);
        } else {
            $this->user = null; // O cualquier valor predeterminado
            $this->purchasedTrees = []; // Inicializa como un array vacío
        }
    }

    public function getAvailableTrees(): array {
        $availableTrees = $this->treeModel->getAvailableTreesWithSpecies();

        foreach ($availableTrees as $tree) {
            $trees[] = [
                'id' => $tree['id'],
                'height' => $tree['height'],
                'location' => htmlspecialchars($tree['location']), // Escape output
                'price' => number_format($tree['price'], 2), // Format price
                'photo_url' => $tree['photo_url'],
                'species' => htmlspecialchars($tree['commercial_name']) // Species information
            ];
        }
        return $trees;
    } 
    public function getUserPurchasedTrees(int $userId): array {
        $purchasedTrees = $this->treeModel->getPurchasedTreesByUser($userId);
        $trees = [];
        
        foreach ($purchasedTrees as $tree) {
            $trees[] = [
                'height' => $tree['height'],
                'location' => htmlspecialchars($tree['location']), // Escape output
                'price' => number_format($tree['price'], 2), // Format price
                'photo_url' => $tree['photo_url'],
                'species' => htmlspecialchars($tree['commercial_name']) // Species information
            ];
        }
        return $trees;
    }

    public function buyTree($userId, $treeId) {
        if (!$this->isTreeAvailable($treeId)) {
            return false;
        }
        $this->registerSale($userId, $treeId);
        $this->markTreeAsSold($treeId);
        return true;
    }
    
    private function isTreeAvailable($treeId) {
        $query = "SELECT available FROM Trees WHERE id = :treeId";
    }
    
    private function registerSale($userId, $treeId) {
        $query = "INSERT INTO Sales (tree_id, buyer_id) VALUES (:treeId, :userId)";
    }
    
    private function markTreeAsSold($treeId) {
        $query = "UPDATE Trees SET available = FALSE WHERE id = :treeId";
    }   
}