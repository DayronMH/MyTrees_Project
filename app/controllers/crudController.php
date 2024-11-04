<?php
require_once '../models/speciesModel.php';
require_once '../models/treesModel.php';
require_once '../views/targetPage.php';
class CrudController
{
    private $speciesModel;
    private $treesModel;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->speciesModel = new SpeciesModel();
        $this->treesModel = new treesModel();
        $this->handlePostActions();
    }

    private function handlePostActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            switch ($_POST['action']) {

                case 'createTrees':
                    $this->createTree();
                    break;
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
                case 'createSpecies':
                    $commercialName = trim($_POST['commercial_name']);
                    $scientificName = trim($_POST['scientific_name']);
                    $speciesId = trim($_POST['species_id']);
                    // Validar que los campos no estén vacíos
                    if (empty($commercialName) || empty($scientificName)) {
                        $_SESSION['error'] = "Todos los campos son requeridos";
                    } else {

                        $success = $this->createSpecie($speciesId, $commercialName, $scientificName);

                        if ($success) {
                            $_SESSION['success'] = "Especie creada correctamente";

                            exit();
                        } else {
                            $_SESSION['error'] = "Error al crear la especie";
                        }
                    }
            }
        }
    }

    public function createSpecie($speciesId, $commercial_name, $scientific_name)
    {
        try {
            // Validar datos de entrada
            if (empty($commercial_name) || empty($scientific_name)) {
                return ['error' => 'Los nombres comercial y científico son requeridos.'];
            }

            // Verificar si la especie ya existe
            if ($this->speciesModel->hasTreesAssociated($speciesId)) {
                return ['error' => 'La especie ya existe en la base de datos.'];
            }

            // Crear la especie
            if ($this->speciesModel->createSpecie($commercial_name, $scientific_name)) {
                $_SESSION['message'] = 'Especie creada correctamente';
                header('Location: ../views/admindashboard.php');
                exit();
            } else {
                return ['error' => 'Error al crear la especie'];
            }
        } catch (Exception $e) {
            return ['error' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }
    public function deleteSpecie($speciesId)
    {
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
    public function getSpecieById($id)
    {
        return $this->speciesModel->getSpeciesById($id);
    }
    public function getSpeciesNames($id)
    {
        return $this->speciesModel->getCommercialNames();
    }

    public function getEditableTreeById($treeId)
    {
        $tree = $this->getEditableTreeById($treeId);
        $_SESSION['height'] = $tree['height'];
        $_SESSION['specie'] = $tree['commercial_name'];
        $_SESSION['location'] = $tree['location'];
        $_SESSION['available'] = $tree['available'];
    }
    private function createTree()
    {
        try {
            // Validar y obtener los datos del formulario
            $speciesId = filter_var($_POST['species_id'] ?? null, FILTER_VALIDATE_INT);
            $location = trim($_POST['location'] ?? '');
            $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);            
            $fileName = basename($_FILES['treepic']['name']);
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . "http://mytrees.com/public/images/";
            $typeImage = strtolower(pathinfo($targetDir, PATHINFO_EXTENSION));
            $photo_url = $targetDir . $fileName;
            echo $fileName;
            // Validación de campos requeridos
            if (!$speciesId) {
                throw new Exception('El ID de la especie es requerido y debe ser válido');
            }
            if (empty($location)) {
                throw new Exception('La ubicación es requerida');
            }
            if ($price <= 0) {
                throw new Exception('El precio debe ser mayor a 0');
            }

           


            // Crear árbol en la base de datos
            $success = $this->treesModel->createTreeBasic(
                $speciesId,
                $location,
                $price,
                $photo_url,
            );

            if (!$success) {
                throw new Exception('Error al crear el árbol en la base de datos');
            }

            setTargetMessage('success', 'Árbol creado correctamente');
            header("Location: ../views/createTree.php");
            exit();
        } catch (Exception $e) {
            setTargetMessage('error', $e->getMessage());
            header("Location: ../views/createTree.php");
            exit();
        }
    }
}
