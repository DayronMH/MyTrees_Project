<?php
session_start();
require_once "../models/usersModel.php";
require_once "../models/treesModel.php";
require_once "../models/speciesModel.php";

class UpdateController {
    public function processUpdate() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = $_POST['model'] ?? '';
            $id = $_POST['id'] ?? '';
            
            if (empty($model) || empty($id)) {
                $_SESSION['error'] = "Datos inválidos";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
            
            $className = ucfirst($model) . 'Model';
            $modelInstance = new $className();
            unset($_POST['model'], $_POST['id']);
            
            $result = $modelInstance->update($id, $_POST);
            
            if ($result) {
                $_SESSION['success'] = "Actualización exitosa";
            } else {
                $_SESSION['error'] = "Error en la actualización";
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}

$controller = new UpdateController();
$controller->processUpdate();
