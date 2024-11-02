<?php
require_once '../models/speciesModel.php';
require_once '../models/treesModel.php';
class CrudController {
    private $speciesModel;
    private $treesModel;
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->speciesModel = new SpeciesModel();
        $this->treesModel = new treesModel();
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

    // Método para obtener una especie por ID (nombre consistente)
    public function getSpecieById($id) {
        return $this->speciesModel->getSpeciesById($id);
    }
    public function getSpeciesNames($id) {
        return $this->speciesModel->getCommercialNames();
    }
    public function getEditableTreeById($treeId){
        $tree =$this->getEditableTreeById($treeId);
        $_SESSION['height'] = $tree['height'];
        $_SESSION['specie'] = $tree['commercial_name'];
        $_SESSION['location'] = $tree['location'];
        $_SESSION['available'] = $tree['available'];
    }

    
}