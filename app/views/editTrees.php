<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once '../controllers/crudController.php';
require_once '../models/treesModel.php';

// Inicializar controladores
$crud = new CrudController();
$trees = new TreesModel();
// Validación del ID
$treeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$treeId) {
    $_SESSION['error'] = "ID de árbol inválido";
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

    if ($_POST['action'] === 'edit_tree') {
        $result = $trees->editTree($treeId, $height, $location, $available);

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
$currentTree = $trees->getTreeById($treesId);
if (!$currentTree) {
    $_SESSION['error'] = "No se encontró el arbol especificada";
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
                    <label for="height">Altura:</label>
                    <input
                        type="text"
                        id="height"
                        name="height"
                        value="<?php echo htmlspecialchars($currentTree['height']); ?>"
                        required
                        class="form-input">
                </div>

                <div class="form-group">
                    <label for="scientific_name">Nombre Científico:</label>
                    <input
                        type="text"
                        id="scientific_name"
                        name="scientific_name"
                        value="<?php echo htmlspecialchars($currentTree['location']); ?>"
                        required
                        class="form-input">
                </div>

                <div class="form-actions">
                    <button type="submit" name="action" value="editTree" class="submit-button">
                        Editar arbol
                    </button>
                    <button type="submit" name="action" value="updateTree" class="submit-button">
                        Actualizar arbol
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
    </div>
</body>

</html>