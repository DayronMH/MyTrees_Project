<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once './targetPage.php';
require_once '../controllers/crudController.php';
$controller = new AdminDashboardController();
$crud = new crudController();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'createSpecies') {
        $commercialName = trim($_POST['commercial_name']);
        $scientificName = trim($_POST['scientific_name']);

        // Validar que los campos no estén vacíos
        if (empty($commercialName) || empty($scientificName)) {
            setTargetMessage('error', 'Todos los campos son requeridos');
        } else {

            $success = $controller->createSpecie($speciesId, $commercialName, $scientificName);

            if ($success) {
                setTargetMessage('success', 'Especie creada correctamente');

                exit();
            } else {
                setTargetMessage('error', 'Error al crear la especie');
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

    <title>Crear Nuevo Especie</title>
</head>

<body>
    <form action="../controllers/crudController.php" metho="POST" class="form-trees" role="form" enctype="multipart/form-data">
        <div class="container">
            <div class="edit-header">
                <h1>Crear Nuevo Arbol</h1>
                <a href="adminDashboard.php" class="back-button">Volver al Dashboard</a>
            </div>

            <div class="form-tree">
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


                <div class="form-tree">
                    <label for="specie">Especie:</label>
                    <input
                        type="text"
                        id="specie"
                        name="specie"
                        value="<?php echo isset($tree[0]['commercial_name']) ? htmlspecialchars($tree[0]['commercial_name']) : ''; ?>"
                        class="form-input">
                </div>

                <div class="form-tree">
                    <label for="price">Precio:</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        class="form-input"
                        placeholder="Ingrese el precio"
                        value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                </div>
                <div class="form-tree">
                    <div>
                        <label>
                            <input type="radio" name="available" value="1" <?php echo (!isset($tree[0]['available']) || $tree[0]['available'] == 1) ? 'checked' : ''; ?>>
                            <span>Disponible</span>
                        </label>
                        <label>
                            <input type="radio" name="available" value="0" <?php echo (isset($tree[0]['available']) && $tree[0]['available'] == 0) ? 'checked' : ''; ?>>
                            <span>Vendido</span>
                        </label>
                    </div>
                </div>

                </select>
                <div class="form-tree">
                    <label for="">Tree Picture</label>
                    <input type="file" class="form-control" name="profilePic" id="profilePic">
                </div>

                <div class="form-actions">
                    <button type="submit" name="action" value="createTrees" class="submit-button">
                        Crear Arbol
                    </button>

                    <a href="adminDashboard.php" class="cancel-button">
                        Cancelar
                    </a>
                </div>

            </div>
        </div>
    </form>
</body>