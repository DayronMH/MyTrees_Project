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
    private $sales;
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
        $this->salesModel = new salesModel();
        // Comprobar si el ID del usuario está en la sesión
        if (isset($_SESSION['user_id'])) {
            $this->user = (int)$_SESSION['user_id']; // Asegúrate de que sea un entero
            $this->purchasedTrees = $this->getUserPurchasedTrees($this->user);
        } else {
            $this->user = null; // O cualquier valor predeterminado
            $this->purchasedTrees = []; // Inicializa como un array vacío
        }
    }

    public function getAvailableTrees() {
        $availableTrees = $this->treeModel->getAvailableTreesWithSpecies();
        $trees = $availableTrees;
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
        if (!$this->salesModel->isTreeAvailable($treeId)) {
            return false; // El árbol no está disponible
        }
        
        // Registrar la venta
        if ($this->salesModel->createSale($userId, $userId)) {
            // Marcar el árbol como vendido
            $this->salesModel->markTreeAsSold($treeId);
            return true; // Compra exitosa
        }
        
        return false; // Fallo en el registro de la venta
    }
    
}