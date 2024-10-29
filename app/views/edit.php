<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once '../models/speciesModel.php';
$controller = new AdminDashboardController();

// Obtener el ID de la especie de la URL
$speciesId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Si no hay ID válido, redirigir al dashboard
if (!$speciesId || $speciesId <= 0) {
    header("Location: adminDashboard.php");
    exit();
}

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $speciesId = (int)$_POST['species_id'];
    $species = new SpeciesModel();

    if ($_POST['action'] === 'updateSpecies') {
        $commercialName = $_POST['commercial_name'];
        $scientificName = $_POST['scientific_name'];

        $success = $species->editSpecies($speciesId, $commercialName, $scientificName);

        if ($success) {
            $_SESSION['success'] = "Especie actualizada correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar la especie";
        }
    } elseif ($_POST['action'] === 'delete_species') {
        // Verificar si la especie tiene árboles asociados
        if ($species->hasTreesAssociated($speciesId)) {
            $_SESSION['error'] = "No se puede eliminar la especie porque tiene árboles asociados.";
        } else {
            if ($species->deleteSpecie($speciesId)) {
                $_SESSION['success'] = "Especie eliminada correctamente";
                header("Location: adminDashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Error al eliminar la especie";
            }
        }
    }
}

// Buscar la especie específica
$speciesData = null;
$species = new SpeciesModel();
$currentSpecies = $species->getSpeciesById($speciesId);

if ($currentSpecies) {
    $speciesData = [
        'commercial_name' => $currentSpecies['commercial_name'],
        'scientific_name' => $currentSpecies['scientific_name'],
        'id' => $speciesId
    ];
} else {
    $_SESSION['error'] = "No se encontró la especie.";
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

        <div class="form-species">
            <form method="POST" onsubmit="return validateForm(this);">
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
                    <button type="submit" name="action" value="updateSpecies" class="submit-button">
                        Actualizar Especie
                    </button>
                    <button type="button" class="cancel-button" onclick="window.location.href='adminDashboard.php'">
                        Cancelar
                    </button>
                    <button type="submit" name="action" value="delete_species" class="delete-btn" onclick="return confirm('¿Estás seguro de eliminar esta especie?');">
                        Eliminar
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
    </div>

    <script>
    function validateForm(form) {
        if (form.action.value === 'delete_species') {
            return confirm('¿Estás seguro de eliminar esta especie?');
        }
        return true;
    }
    </script>
</body>
</html>