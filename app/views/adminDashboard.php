<?php

/**
 * Admin Dashboard View
 * 
 * This file displays the main administrative dashboard interface.
 * It shows statistics, species management, and registered friends.
 * 
 * PHP version 7.4+
 * 
 * @category View
 * @package  MyTrees
 * @author   Your Name
 * @license  MIT License
 */

session_start();

require_once '../controllers/adminDashboardController.php';
require_once '../controllers/crudController.php';
require_once 'targetPage.php';

// Authentication check
if (!isset($_SESSION['user_id'])) {
    setTargetMessage('error', 'Username must be');
    header('Location: http://mytrees.com');
    exit();
}

// Initialize dashboard controller
$dashboard = new AdminDashboardController();

// Process POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dashboard->handlePostActions();
}

require_once 'targetPage.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/dashboard.css">
    <title>Panel Administrador</title>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="dashboard-header">
            <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <hr>
            <h2>Estadísticas</h2>
        </div>

        <!-- Statistics Cards Section -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo htmlspecialchars($_SESSION['friendsCount'] ?? 0); ?>

                    </div>
                    <div class="stat-title">Amigos Registrados</div>
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo htmlspecialchars($_SESSION['availableTreesCount'] ?? 0); ?>

                    </div>
                    <div class="stat-title">Árboles Disponibles</div>
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo htmlspecialchars($_SESSION['soldTreesCount'] ?? 0); ?>

                    </div>
                    <div class="stat-title">Árboles Vendidos</div>
                </div>
            </div>
        </div>

        <div class="dashboard-header">
            <h2>Especies</h2>
        </div>

        <div class="species-container">
            <?php
            $commercial_names = $_SESSION['commercial_names'];
            $scientific_names = $_SESSION['scientific_names'];

            for ($i = 0; $i < count($commercial_names); $i++) {
                $commercial = $commercial_names[$i]['commercial_name'];
                $scientific = $scientific_names[$i]['scientific_name'];
                $speciesId = $commercial_names[$i]['id'];
            ?>
                <div class="species-card">
                    <div class="species-info">
                        <h3 class="species-name"><?php echo htmlspecialchars($commercial); ?></h3>
                        <div class="species-content">
                            <p class="species-description" id="scientific_<?php echo $speciesId; ?>" style="display: none;">
                                <strong>Nombre Científico:</strong>
                                <?php echo htmlspecialchars($scientific); ?>
                            </p>
                        </div>
                        <div class="species-actions">
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="species_id" value="<?php echo $speciesId; ?>">

                                <!-- Botón Ver/Ocultar -->
                                <button type="submit" name="action" value="view_species" class="action-button view-btn">
                                    Ver
                                </button>

                                <!-- Botón Editar -->
                                <button type="submit" name="action" value="edit_species" class="action-button edit-btn">
                                    Editar
                                </button>

                                <!-- Botón Eliminar -->
                                <button type="submit"
                                    name="action"
                                    value="delete_species"
                                    class="action-button delete-btn"
                                    onclick="return confirm('¿Estás seguro de eliminar esta especie?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
    function toggleScientificName(speciesId) {
        const scientificElement = document.getElementById(`scientific_${speciesId}`);
        const viewButton = document.getElementById(`viewBtn_${speciesId}`);
        
        if (scientificElement.style.display === 'none') {
            scientificElement.style.display = 'block';
            viewButton.textContent = 'Ocultar';
        } else {
            scientificElement.style.display = 'none';
            viewButton.textContent = 'Ver';
        }
    }
    </script>
</body>
=======
        <!-- Species Management Section -->
        <div class="dashboard-section">
            <div class="dashboard-header">
                <h2>Especies</h2>
            </div>

            <div class="species-container">
                <?php
                $commercial_names = $_SESSION['commercial_names'] ?? [];
                $scientific_names = $_SESSION['scientific_names'] ?? [];

                foreach ($commercial_names as $index => $commercial_name):
                    $commercial = htmlspecialchars($commercial_name['commercial_name']);
                    $scientific = htmlspecialchars($scientific_names[$index]['scientific_name']);
                    $speciesId = htmlspecialchars($commercial_name['id']);
                    $isVisible = isset($_SESSION['visible_species']) && in_array($speciesId, $_SESSION['visible_species']);
                ?>
                    <div class="species-card">
                        <div class="species-info">
                            <h3 class="species-name"><?php echo $commercial; ?></h3>
                            <div class="species-content">
                                <p class="species-description" style="display: <?php echo $isVisible ? 'block' : 'none'; ?>">
                                    <strong>Nombre Científico:</strong> <?php echo $scientific; ?>
                                </p>
                            </div>

                            <div class="species-actions">
                                <form method="POST" action="" class="view-form">
                                    <input type="hidden" name="species_id" value="<?php echo $speciesId; ?>">
                                    <button type="submit" name="action" value="view_species" class="view-btn">
                                        <?php echo $isVisible ? 'Ocultar' : 'Ver'; ?>
                                    </button>
                                </form>
                                <button onclick="window.location.href='../views/edit.php?id=<?php echo $speciesId; ?>'"
                                    class="action-button edit-btn">
                                    Editar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="button-container">

                </div>
            </div>
        </div>

        <!-- Friends List Section -->
        <div class="dashboard-section">
            <div class="dashboard-header">
                <h2>Amigos Registrados:</h2>
            </div>

            <div class="friends-container">
                <?php
                $friends = $_SESSION['friends'] ?? [];
                foreach ($friends as $friend): ?>
                    <a href="trees.php?friend_id=<?php echo htmlspecialchars($friend['id']); ?>" class="friend-link">
                        <h3 class="friend-name"><?php echo htmlspecialchars($friend['name']); ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <br>
        <div class="button-container">
            <div class="dashboard-header">
                <h2>Agregar </h2>
            </div>


            <button onclick="window.location.href='../views/createTree.php'"
                class="create-btn"
                name="action"
                value="create_tree">
                Arbol
            </button>
            <div class="dashboard-header">
                <h2>Agregar</h2>
            </div>



            <button onclick="window.location.href='../views/createSpecie.php'"
                class="create-btn">
                Especie
            </button>
        </div>

        <!-- Logout Button -->
        <div class="dashboard-footer">
            <a href="login.php" class="back-button">← Cerrar Sesión</a>
        </div>
    </div>
</body>

</html>
