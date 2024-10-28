<?php
session_start();
require_once '../controllers/adminDashboardController.php';
$data = new AdminDashboardController();
?>
<!DOCTYPE html>
< lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="http://mytrees.com/public/dashboard.css">
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
                            <?php echo htmlspecialchars($_SESSION['friendsCount']) ?>
                        </div>
                        <div class="stat-title">Amigos Registrados</div>
                    </div>
                </div>

                <!-- Card Árboles Disponibles -->
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-number">
                            <?php echo htmlspecialchars($_SESSION['availableTreesCount']) ?>
                        </div>
                        <div class="stat-title">Árboles Disponibles</div>
                    </div>
                </div>

                <!-- Card Árboles Vendidos -->
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-number">
                            <?php echo htmlspecialchars($_SESSION['soldTreesCount']) ?>
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
                                <p class="species-description" id="scientific_<?php echo $speciesId; ?>"
                                    style="display: <?php echo (isset($_SESSION['visible_species']) &&
                                                        in_array($speciesId, $_SESSION['visible_species'])) ? 'block' : 'none'; ?>">
                                    <strong>Nombre Científico:</strong>
                                    <?php echo htmlspecialchars($scientific); ?>
                                </p>
                            </div>
                            <div class="species-actions">
                                <form method="POST" action="" style="display: inline;" class="view-form">
                                    <input type="hidden" name="species_id" value="<?php echo $speciesId; ?>">
                                    <button type="submit" name="action" value="view_species" class="action-button view-btn">
                                        <?php echo (isset($_SESSION['visible_species']) &&
                                            in_array($speciesId, $_SESSION['visible_species'])) ? 'Ocultar' : 'Ver'; ?>
                                    </button>
                                </form>

                                <button onclick="window.location.href='../views/edit.php?id=<?php echo $speciesId; ?>'"
                                    class="action-button edit-btn"
                                    name="action"
                                    value="edit_species">
                                    Editar
                                </button>

                                <form method="POST" action="" style="display: inline;" class="delete-form">
                                    <input type="hidden" name="species_id" value="<?php echo $speciesId; ?>">
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

            <div class="dashboard-header">
                <h2>Amigos Registrados:</h2>
            </div>
            <div class="friends-container">
                <?php
                $friends = $_SESSION['friends'];
                foreach ($friends as $f) {
                    $friendName = $f['name'];
                    $friendId = $f['id']; // Asumiendo que tienes el id en el array
                ?>
                    <a href="trees.php?friend_id=<?php echo htmlspecialchars($friendId); ?>" class="friend-link">
                        <h3 class="friend-name"><?php echo htmlspecialchars($friendName); ?></h3>
                    </a>
                <?php } ?>
            </div>
        </div>

    </body>

    </html>