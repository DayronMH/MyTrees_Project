<?php
session_start();
require_once '../controllers/adminDashboardController.php';
$data = new AdminDashboardController();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://mytrees.com/public/Dashboard.css">
    <title>Administrador</title>
</head>

<body>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <?php
            echo "<h1>Bienvenido " . htmlspecialchars($_SESSION['username']) . "</h1>";
            ?>
            <hr>
            <h2>Estadísticas</h2>
        </div>
        <div class="stats-container">
            <!-- Card Amigos -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo ($_SESSION['friendsCount'])  ?>
                    </div>
                    <div class="stat-title">Amigos Registrados</div>
                </div>
            </div>

            <!-- Card Árboles Disponibles -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo ($_SESSION['availableTreesCount'])  ?>
                    </div>
                    <div class="stat-title">Árboles Disponibles</div>
                </div>
            </div>

            <!-- Card Árboles Vendidos -->
            <div class="stat-card">
                <div class="stat-content">
                    <div class="stat-number">
                        <?php echo ($_SESSION['soldTreesCount'])  ?>
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
