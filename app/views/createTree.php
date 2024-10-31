<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once '../controllers/crudController.php';
$controller = new AdminDashboardController();
$crud = new crudController();
$species = $crud->getSpeciesNames();
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

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'createSpecies') {
        $commercialName = trim($_POST['commercial_name']);
        $scientificName = trim($_POST['scientific_name']);
        
        // Validar que los campos no estén vacíos
        if (empty($commercialName) || empty($scientificName)) {
            $_SESSION['error'] = "Todos los campos son requeridos";
        } else {

            $success = $controller->createSpecie($speciesId,$commercialName, $scientificName);

            if ($success) {
                $_SESSION['success'] = "Especie creada correctamente";
               
                exit();
            } else {
                $_SESSION['error'] = "Error al crear la especie";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://mytrees.com/public/edit.css">
    <title>Crear Nueva Especie</title>
</head>
<body>
    <div class="container">
        <div class="edit-header">
            <h1>Crear Nueva Especie</h1>
            <a href="adminDashboard.php" class="back-button">← Volver al Dashboard</a>
        </div>
        
        <div class="form-species">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <?php
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="location">Ubicacion Geografica:</label>
                    <input
                        type="text"
                        id="location"
                        name="location"
                        class="form-input"
                        placeholder="Ingrese la ubicacion"
                        value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="price">Precio:</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        required
                        class="form-input"
                        placeholder="Ingrese el precio"
                        value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                </div>
                <label for="province">Provincia:</label><br>
        <select id="specie" name="specie">
        <?php
        if (!empty($species) && is_array($species)) {
            foreach ($species as $specie) {
                $specie_id = isset($specie['id']) ? $specie['id'] : null;
                $specie_name = isset($specie['commercial_name']) ? $specie['commercial_name'] : null;

                if ( $specie_id !== null && $specie_id ) {
                    ?>
                    <option value="<?php echo htmlspecialchars($province_id); ?>">
                        <?php echo htmlspecialchars($specie_name); ?>
                    </option>
                    <?php
                }
            }
            } else {
                echo '<option>No hay provincias disponibles</option>';
            }
            ?>
        </select>
                <div class="form-actions">
                    <button type="submit" name="action" value="createTrees" class="submit-button">
                        Crear Arbol
                    </button>
                    <a href="adminDashboard.php" class="cancel-button">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>sr