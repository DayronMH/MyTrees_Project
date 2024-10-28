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
    
    public function __construct() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UsersModel();
        $this->treeModel = new TreesModel();
        $this->speciesModel = new SpeciesModel();
        
        $this->getAvailableTrees();
    }

    public function getAvailableTrees() {
        // Obtener los árboles disponibles
        $availableTrees = $this->treeModel->getAvailableTrees();
        
        // Inicializar arrays para almacenar la información separada
        $treeSpecies = [];
        $treeHeights = [];
        $treeLocations = [];
        $treePrices = [];
        $treePhotos = [];

        // Procesar cada árbol disponible
        foreach ($availableTrees as $tree) {
            // Asumiendo que $tree es un array asociativo con los datos del árbol
            $treeSpecies[] = $tree['commercial_name'];
            $treeHeights[] = $tree['height'];
            $treeLocations[] = $tree['location'];
            $treePrices[] = $tree['price'];
            $treePhotos[] = $tree['photo_url'];
        }
        
        // Almacenar la información en la sesión
        $_SESSION['tree_species'] = $treeSpecies;
        $_SESSION['tree_heights'] = $treeHeights;
        $_SESSION['tree_locations'] = $treeLocations;
        $_SESSION['tree_prices'] = $treePrices;
        $_SESSION['tree_photos'] = $treePhotos;
        
    }
}