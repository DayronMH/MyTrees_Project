<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once '../controllers/crudController.php';
$controller = new AdminDashboardController();
$crud = new crudController();
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
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
                    <label for="commercial_name">Nombre Comercial:</label>
                    <input
                        type="text"
                        id="commercial_name"
                        name="commercial_name"
                        required
                        class="form-input"
                        placeholder="Ingrese el nombre comercial"
                        value="<?php echo isset($_POST['commercial_name']) ? htmlspecialchars($_POST['commercial_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="scientific_name">Nombre Científico:</label>
                    <input
                        type="text"
                        id="scientific_name"
                        name="scientific_name"
                        required
                        class="form-input"
                        placeholder="Ingrese el nombre científico"
                        value="<?php echo isset($_POST['scientific_name']) ? htmlspecialchars($_POST['scientific_name']) : ''; ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="action" value="createSpecies" class="submit-button">
                        Crear Especie
                    </button>
                    <a href="adminDashboard.php" class="cancel-button">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>