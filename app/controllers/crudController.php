<?php
require_once '../models/speciesModel.php';

class crudController{
    private $speciesModel;

public function __construct()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $this->speciesModel = new SpeciesModel();
    $this->handlePostActions();
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


    public function deleteSpecie($speciesId)
    {
        // Inicializar variables
        $success = null;
        $error = null;
    
        // Verificar si la especie tiene árboles asociados
        $hasAssociatedTrees = $this->speciesModel->hasTreesAssociated($speciesId);
        
        if ($hasAssociatedTrees) {
            $error = 'No se puede eliminar la especie porque tiene árboles asociados.';
        } else {
            if ($this->speciesModel->deleteSpecie($speciesId)) {
                $success = "Especie eliminada correctamente";
            } else {
                $error = 'Error al eliminar la especie';
            }
        }
        
    return [
        'success' => $success,
        'error' => $error,
    ];
    
    }
    
    public function createSpecie($commercial_name,$scientific_name,$date){
        date_default_timezone_set('America/Costa_Rica');

        $date = date('Y-m-d H:i:s');
        if($this->speciesModel->createSpecie($commercial_name,$scientific_name,$date)){
            $_SESSION['success'] = "Especie creada correctamente";
        } else {
            $_SESSION['error'] = "Error al crear la especie";
        }
        // Redirigir de vuelta a la página actual
        header('Location: '. $_SERVER['PHP_SELF']);
        exit();

    }
}