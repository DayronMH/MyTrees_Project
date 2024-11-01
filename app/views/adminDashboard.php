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

// Initialize dashboard controller
$dashboard = new AdminDashboardController();

// Process post actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dashboard->handlePostActions();
}

// Authentication check
if (!isset($_SESSION['username'])) {
    header('Location: http://mytrees');
    exit();
}
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
            <!-- Registered Friends Stat -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo htmlspecialchars($_SESSION['friendsCount'] ?? 0); ?>
                    </div>
                    <div class="stat-title">Amigos Registrados</div>
                </div>
            </div>

            <!-- Available Trees Stat -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo htmlspecialchars($_SESSION['availableTreesCount'] ?? 0); ?>
                    </div>
                    <div class="stat-title">Árboles Disponibles</div>
                </div>
            </div>

            <!-- Sold Trees Stat -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo htmlspecialchars($_SESSION['soldTreesCount'] ?? 0); ?>
                    </div>
                    <div class="stat-title">Árboles Vendidos</div>
                </div>
            </div>
        </div>

        <!-- Species Management Section -->
        <div class="dashboard-section">
            <div class="dashboard-header">
                <h2>Especies</h2>
            </div>

            <div class="species-container">
                <?php
                // Get species data from session
                $commercial_names = $_SESSION['commercial_names'] ?? [];
                $scientific_names = $_SESSION['scientific_names'] ?? [];

                // Loop through each species
                for ($i = 0; $i < count($commercial_names); $i++):
                    $commercial = $commercial_names[$i]['commercial_name'];
                    $scientific = $scientific_names[$i]['scientific_name'];
                    $speciesId = $commercial_names[$i]['id'];
                    $isVisible = isset($_SESSION['visible_species']) && in_array($speciesId, $_SESSION['visible_species']);
                ?>
                    <div class="species-card">
                        <!-- Species Information -->
                        <div class="species-info">
                            <h3 class="species-name"><?php echo htmlspecialchars($commercial); ?></h3>
                            <div class="species-content">
                                <p class="species-description" 
                                   style="display: <?php echo $isVisible ? 'block' : 'none'; ?>">
                                    <strong>Nombre Científico:</strong>
                                    <?php echo htmlspecialchars($scientific); ?>
                                </p>
                            </div>
                            
                            <!-- Species Action Buttons -->
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
                <?php endfor; ?>

                <!-- Create New Species Button -->
                <button onclick="window.location.href='../views/createSpecie.php'"
                        class="create-btn">
                    Crear Especie
                </button>
            </div>
        </div>

        <!-- Friends List Section -->
        <div class="dashboard-section">
            <div class="dashboard-header">
                <h2>Amigos Registrados:</h2>
            </div>

            <div class="friends-container">
                <?php
                // Display friends list
                $friends = $_SESSION['friends'] ?? [];
                foreach ($friends as $friend): ?>
                    <a href="trees.php?friend_id=<?php echo htmlspecialchars($friend['id']); ?>" 
                       class="friend-link">
                        <h3 class="friend-name"><?php echo htmlspecialchars($friend['name']); ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="dashboard-footer">
            <a href="login.php" class="back-button">← Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>