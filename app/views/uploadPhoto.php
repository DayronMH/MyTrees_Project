<?php
require_once '../models/treesModel.php';
class uploadPhoto {
    private $uploadDirectory;
    private $treesModel;
    public function __construct() {
        $treesModel = new treesModel();
        $this->uploadDirectory = __DIR__ . 'http://mytrees.com/public/images/';
        if (!file_exists($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0755, true);
        }
    }

    public function handleImageUpload($imageFile) {
        try {
            if (!isset($imageFile) || $imageFile['error'] !== UPLOAD_ERR_OK) {
                return '';
            }

            // Generar nombre único
            $extension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            
            // Ruta completa del archivo
            $filepath = $this->uploadDirectory . $filename;
            
            // Mover el archivo
            move_uploaded_file($imageFile['tmp_name'], $filepath);
            
            // Retornar la ruta relativa para la base de datos
            return 'images/trees/' . $filename;

        } catch (Exception $e) {
            return '';
        }
    }

    public function createTreeUpdate($postData, $files) {
        // Obtener la ruta de la imagen si se subió una
        $imageUrl = isset($files['image']) ? $this->handleImageUpload($files['image']) : '';
        
        // Crear la actualización usando la ruta de la imagen
        return $this->treesModel->createTreeUpdate(
            $postData['tree_id'],
            $postData['size'],
            $postData['height'],
            $postData['growth_rate'],
            $postData['health_status'],
            $imageUrl,
            $postData['status']
        );
    }
}