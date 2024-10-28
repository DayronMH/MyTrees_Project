<?php
session_start();
require_once '../models/treesModel.php';

$friend_id = isset($_GET['friend_id']) ? $_GET['friend_id'] : null;

if (is_null($friend_id)) {
    header('Location: adminDashboard.php');
    exit;
}

$treesModel = new TreesModel();
$trees = $treesModel->getTreesByowner($friend_id);

if (empty($trees)) {
    echo "<script>
            alert('Este amigo no tiene arboles');
            window.location.href = 'http://mytrees.com/app/views/adminDashboard.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://mytrees.com/public/dashboard.css">
    <title>Árboles de <?php echo htmlspecialchars($trees[0]['owner_name']); ?></title>
</head>

<body>
    <div class="container">
        <div class="view-header">
            <h1>Árboles de <?php echo htmlspecialchars($trees[0]['owner_name']); ?></h1>
            <a href="adminDashboard.php" class="back-button">← Volver al Dashboard</a>
        </div>

        <div class="trees-container">
            <?php foreach ($trees as $tree): ?>
                <div class="tree-card">
                    <?php if ($tree['photo_url']): ?>
                        <img src="<?php echo htmlspecialchars($tree['photo_url']); ?>" alt="Foto del árbol" class="tree-image">
                    <?php endif; ?>

                    <div class="tree-details">
                        <h2 class="specie_name"><strong>Especie:</strong> <?php echo htmlspecialchars($tree['commercial_name']); ?></h2>

                        <div class="tree-info">
                            <p><strong>Altura:</strong> <?php echo htmlspecialchars($tree['height']); ?> metros</p>
                            <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($tree['location']); ?></p>
                            <p><strong>Precio:</strong> $<?php echo htmlspecialchars($tree['price']); ?></p>
                            <p class="status <?php echo $tree['available'] ? 'available' : 'sold'; ?>">
                                <?php echo $tree['available'] ? 'Disponible' : 'Vendido'; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="trees-actions">
                 
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
            <?php endforeach; ?>
        </div>

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
</body>

</html>