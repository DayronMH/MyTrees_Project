<?php
session_start();
require_once '../controllers/adminDashboardController.php';
require_once '../controllers/crudController.php';

// Initialize dashboard controller
$data = new AdminDashboardController();

// Verify user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
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

        <!-- Statistics Section -->
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

        <!-- Species Section -->
        <div class="dashboard-header">
            <h2>Especies</h2>
        </div>

        <div class="species-container">
            <?php
            $commercial_names = $_SESSION['commercial_names'] ?? [];
            $scientific_names = $_SESSION['scientific_names'] ?? [];

            for ($i = 0; $i < count($commercial_names); $i++):
                $commercial = $commercial_names[$i]['commercial_name'];
                $scientific = $scientific_names[$i]['scientific_name'];
                $speciesId = $commercial_names[$i]['id'];
                $isVisible = isset($_SESSION['visible_species']) && in_array($speciesId, $_SESSION['visible_species']);
            ?>
                <div class="species-card">
                    <div class="species-info">
                        <h3 class="species-name"><?php echo htmlspecialchars($commercial); ?></h3>
                        <div class="species-content">
                            <p class="species-description" id="scientific_<?php echo $speciesId; ?>"
                               style="display: <?php echo $isVisible ? 'block' : 'none'; ?>">
                                <strong>Nombre Científico:</strong>
                                <?php echo htmlspecialchars($scientific); ?>
                            </p>
                        </div>
                        
                        <div class="species-actions">
                            <form method="POST" action="../controllers/crudController.php" class="view-form">
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

            <button onclick="window.location.href='../views/createSpecie.php'"
                    class="create-btn">
                Crear especie
            </button>
        </div>

        <!-- Friends Section -->
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
    <a href="login.php" class="back-button">← Cerrar Sesión</a>
</body>
</html>