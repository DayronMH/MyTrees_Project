<?php
require_once '../controllers/AdminDashboardController.php';
$dashboard = new AdminDashboardController();
$data = $dashboard->getData();
$userName = htmlspecialchars($data['userName']);
$countUsers = htmlspecialchars($data['countUsers']);
$countAvailableTrees = htmlspecialchars($data['countAvailableTrees']);
$countSoldTrees = htmlspecialchars($data['countSoldTrees']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - MyTrees</title>
    <link rel="stylesheet" href="https://mytrees.com/public/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <div class="welcome-message">
            <h1>Bienvenido, <?php echo $userName; ?></h1>
            <p>Panel de administración de MyTrees</p>
        </div>

        <form method="POST" class="logout-form">
            <button type="submit" name="action" value="logOut" class="btn btn-danger">Cerrar Sesión</button>
        </form>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3>Amigos Registrados</h3>
                <div class="number"><?php echo $countUsers; ?></div>
            </div>
            <div class="stat-card">
                <h3>Árboles Disponibles</h3>
                <div class="number"><?php echo $countAvailableTrees; ?></div>
            </div>
            <div class="stat-card">
                <h3>Árboles Vendidos</h3>
                <div class="number"><?php echo $countSoldTrees; ?></div>
            </div>
        </div>

        <table class="species-table">
            <tr>
                <th>Nombre Comercial</th>
                <th>Nombre Científico</th>
                <th>Acciones</th>
            </tr>
            <?php if ($data['speciesList']): ?>
                <?php foreach ($data['speciesList'] as $species): 
                    $commercialName = htmlspecialchars($species['commercial_name']);
                    $scientificName = htmlspecialchars($species['scientific_name']);
                ?>
                    <tr>
                        <td><?php echo $commercialName; ?></td>
                        <td><?php echo $scientificName; ?></td>
                        <td>
                            <form action="" method="POST" class="species-form">
                                <input type="hidden" name="species_id" value="<?php echo $species['id']; ?>">
                                <input type="text" name="new_commercial_name" placeholder="Nuevo Nombre Comercial" required class="input-field">
                                <input type="text" name="new_scientific_name" placeholder="Nuevo Nombre Científico" required class="input-field">
                                <button type="submit" name="update_species" class="btn btn-primary">Actualizar</button>
                                <button type="submit" name="delete_species" class="btn btn-danger" 
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta especie?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No hay especies disponibles.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
