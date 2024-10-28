<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once '../models/speciesModel.php';
$controller = new AdminDashboardController();

// Verificar si es una solicitud POST para actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateSpecies') {
    $speciesId = $_POST['species_id'];
    $commercialName = $_POST['commercial_name'];
    $scientificName = $_POST['scientific_name'];
    
    $species = new SpeciesModel();
    $success = $species->editSpecies($speciesId, $commercialName, $scientificName);
    
    if ($success) {
        $_SESSION['success'] = "Especie actualizada correctamente";
    } else {
        $_SESSION['error'] = "Error al actualizar la especie";
    }
    
    // Redirigir al dashboard después de la actualización
    header("Location: adminDashboard.php");
    exit();
}

// Obtener el ID de la especie de la URL
$speciesId = isset($_GET['id']) ? $_GET['id'] : null;

// Buscar la especie específica en los arrays de session
$speciesData = null;
$commercial_names = $_SESSION['commercial_names'];
$scientific_names = $_SESSION['scientific_names'];

// Encontrar la especie específica que coincida con el ID
foreach ($commercial_names as $index => $species) {
    if ($species['id'] == $speciesId) {
        $speciesData = [
            'commercial_name' => $species['commercial_name'],
            'scientific_name' => $scientific_names[$index]['scientific_name'],
            'id' => $speciesId
        ];
        break;
    }
}

// Si no se encuentra la especie, redirigir al dashboard
if (!$speciesData) {
    header("Location: adminDashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://mytrees.com/public/edit.css">
    <title>Editar Especie</title>
</head>
<body>
    <div class="container">
        <div class="edit-header">
            <h1>Editar Especie</h1>
            <a href="adminDashboard.php" class="back-button">← Volver al Dashboard</a>
        </div>

        <form class="form-species" method="POST">
            <input type="hidden" name="action" value="updateSpecies">
            <input type="hidden" name="species_id" value="<?php echo htmlspecialchars($speciesId); ?>">

            <div class="form-group">
                <label for="commercial_name">Nombre Comercial:</label>
                <input
                    type="text"
                    id="commercial_name"
                    name="commercial_name"
                    value="<?php echo htmlspecialchars($speciesData['commercial_name']); ?>"
                    required
                    class="form-input">
            </div>

            <div class="form-group">
                <label for="scientific_name">Nombre Científico:</label>
                <input
                    type="text"
                    id="scientific_name"
                    name="scientific_name"
                    value="<?php echo htmlspecialchars($speciesData['scientific_name']); ?>"
                    required
                    class="form-input">
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-button">
                    Actualizar Especie
                </button>
                <button type="button" class="cancel-button" onclick="window.location.href='adminDashboard.php'">
                    Cancelar
                </button>
            </div>
        </form>

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
    </div>
</body>
</html>