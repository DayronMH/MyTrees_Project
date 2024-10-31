<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once '../controllers/crudController.php';
require_once '../models/speciesModel.php';

// Inicializar controladores
$crud = new CrudController();

// Validación del ID
$speciesId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$speciesId) {
    $_SESSION['error'] = "ID de especie inválido";
    header("Location: adminDashboard.php");
    exit();
}

// Manejar POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['action'])) {
        $_SESSION['error'] = "Acción no especificada";
        header("Location: adminDashboard.php");
        exit();
    }

    $speciesId = filter_input(INPUT_POST, 'species_id', FILTER_VALIDATE_INT);

    if ($_POST['action'] === 'delete_species') {
        $result = $speciesController->deleteSpecie($speciesId);

        // Comprobamos el resultado
        if (isset($result['success'])) {
            $success_message = $result['success']; // Mensaje de éxito si la eliminación fue exitosa
        } elseif (isset($result['error'])) {
            $error_message = $result['error']; // Mensaje de error si hubo algún problema
        }
    } else if ($_POST['action'] === 'updateSpecies') {
        $commercialName = $_POST['commercial_name'];
        $scientificName = $_POST['scientific_name'];

        $species = new SpeciesModel();
        $success = $species->editSpecies($speciesId, $commercialName, $scientificName);

        if ($success) {
            $_SESSION['success'] = "Especie actualizada correctamente";
            header("Location: adminDashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Error al actualizar la especie";
        }
    }
}
// Obtener datos de la especie usando el método correcto
$currentSpecies = $crud->getSpecieById($speciesId);
if (!$currentSpecies) {
    $_SESSION['error'] = "No se encontró la especie especificada";
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
                        value="<?php echo htmlspecialchars($currentSpecies['commercial_name']); ?>"
                        required
                        class="form-input">
                </div>

                <div class="form-group">
                    <label for="scientific_name">Nombre Científico:</label>
                    <input
                        type="text"
                        id="scientific_name"
                        name="scientific_name"
                        value="<?php echo htmlspecialchars($currentSpecies['scientific_name']); ?>"
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