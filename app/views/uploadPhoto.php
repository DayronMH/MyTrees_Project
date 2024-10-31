<?php include 'upload_form.php'; ?>

Ejemplo de uso completo en tu página:

phpCopy<?php
session_start();
require_once 'process_upload.php';

// En tu script de procesamiento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = processImageUpload('image');
    
    if ($result['success']) {
        // Ejemplo: Guardar en la base de datos
        $imagePath = $result['path'];
        $userId = $_SESSION['user_id']; // Asumiendo que tienes el ID del usuario en sesión
        
        $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$imagePath, $userId])) {
            setTargetMessage('success', "Imagen de perfil actualizada");
        } else {
            setTargetMessage('error', "Error al actualizar la base de datos");
        }
        
    } else {
        setTargetMessage('error', $result['error']);
    }
    
    header("Location: target.php");
    exit();
}
?>
<!-- upload_form.php -->
<form action="process_upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
    <div class="file-input-container">
        <input type="file" name="image" id="image" accept="image/*" class="file-input" required>
        <label for="image" class="file-label">
            <span class="file-icon">📸</span>
            <span class="file-text">Seleccionar imagen</span>
        </label>
        <div id="file-name" class="file-name"></div>
    </div>
    <button type="submit" class="upload-button">Subir imagen</button>
</form>


<script>
document.getElementById('image').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    document.getElementById('file-name').textContent = fileName || '';
});
</script>

<?php
// process_upload.php
function processImageUpload($inputName, $targetDir = "uploads/") {
    try {
        // Verificar si existe el directorio de subidas
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $file = $_FILES[$inputName];
        
        // Validaciones básicas
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error en la subida del archivo");
        }

        // Validar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Tipo de archivo no permitido. Solo se permiten imágenes JPG, PNG y GIF");
        }

        // Validar tamaño (máximo 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB en bytes
        if ($file['size'] > $maxSize) {
            throw new Exception("El archivo es demasiado grande. Máximo 5MB permitido");
        }

        // Generar nombre único para el archivo
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $targetDir . $uniqueName;

        // Mover el archivo
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Error al guardar el archivo");
        }

        return [
            'success' => true,
            'filename' => $uniqueName,
            'path' => $targetPath
        ];

    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Ejemplo de uso en tu código:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $result = processImageUpload('image');
    
    if ($result['success']) {
        // Guardar la ruta en la base de datos si es necesario
        $imagePath = $result['path'];
        
        setTargetMessage('success', "Imagen subida correctamente");
        header("Location: target.php");
        exit();
    } else {
        setTargetMessage('error', $result['error']);
        header("Location: target.php");
        exit();
    }
}
?>