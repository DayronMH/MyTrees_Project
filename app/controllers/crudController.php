<?php
require_once '../models/speciesModel.php';

class CrudController {
    private $speciesModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->speciesModel = new SpeciesModel();
        $this->handlePostActions();
    }

    private function handlePostActions() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'edit_species':
                    if (isset($_POST['species_id'])) {
                        $speciesId = $_POST['species_id'];
                        header("Location: ../views/edit.php?id=$speciesId");
                        exit();
                    }
                    break;

                case 'delete_species':
                    if (isset($_POST['species_id'])) {
                        $result = $this->deleteSpecie($_POST['species_id']);
                        $_SESSION['message'] = $result['success'] ?? $result['error'];
                        if (isset($result['success'])) {
                            header('Location: adminDashboard.php');
                        } else {
                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                        }
                        exit();
                    }
                    break;
            }
        }
    }

    public function deleteSpecie($speciesId) {
        try {
            // Verificar si la especie existe
            $specie = $this->speciesModel->getSpeciesById($speciesId);
            if (!$specie) {
                return ['error' => 'La especie no existe.'];
            }

            // Verificar si tiene árboles asociados
            if ($this->speciesModel->hasTreesAssociated($speciesId)) {
                return ['error' => 'No se puede eliminar la especie porque tiene árboles asociados.'];
            }

            // Intentar eliminar la especie
            if ($this->speciesModel->deleteSpecie($speciesId)) {
                return ['success' => 'Especie eliminada correctamente'];
            } else {
                return ['error' => 'Error al eliminar la especie'];
            }
        } catch (Exception $e) {
            return ['error' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }
<<<<<<< Updated upstream
    
    public function createSpecie($commercial_name,$scientific_name){
        
        if($this->speciesModel->createSpecie($commercial_name,$scientific_name)){
            $_SESSION['success'] = "Especie creada correctamente";
        } else {
            $_SESSION['error'] = "Error al crear la especie";
        }
        // Redirigir de vuelta a la página actual
        header('Location: '. $_SERVER['PHP_SELF']);
        exit();
=======
>>>>>>> Stashed changes

    // Método para obtener una especie por ID (nombre consistente)
    public function getSpecieById($id) {
        return $this->speciesModel->getSpeciesById($id);
    }
}