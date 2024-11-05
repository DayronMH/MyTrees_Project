<?php
session_start();
require_once '../controllers/friendDashboardController.php';
require_once '../models/treesModel.php';
require_once '../controllers/salesController.php';
require_once '../controllers/treeController.php';

$friendDashboardController = new FriendDashboardController();
$salesController = new SalesController();
$treeController = new TreeController();

$userId = $_SESSION['user_id'] ?? null;
$user = $friendDashboardController->getUserById($userId);
$getAvailableTrees = $friendDashboardController->getAvailableTrees();
$getPurchasedTrees = $friendDashboardController->getTreesByOwnerId($user['id']);


//Trees that the user has 
//echo "<pre>";
//print_r($getPurchasedTrees);
//echo "</pre>";

//Available trees
//echo "<pre>";
//print_r($getAvailableTrees);
//echo "</pre>";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'buy_tree' && isset($_POST['tree_id'])) {
    $treeId = (int)$_POST['tree_id'];
    $result = $salesController->createSale($userId, $treeId);

    if ($result) {
        $_SESSION['success'] = "Árbol comprado exitosamente.";
    } else {
        $_SESSION['error'] = "Ocurrió un problema al intentar comprar el árbol.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/friend.css">
    <title>Amigo - Panel de Control</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-header {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .dashboard-header h1 {
            color: #2c3e50;
            margin: 0 0 10px 0;
        }

        .dashboard-header h2 {
            color: #34495e;
            margin: 10px 0;
        }

        hr {
            border: none;
            border-top: 2px solid #eee;
            margin: 15px 0;
        }

        /* Contenedor de árboles */
        .trees-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        /* Tarjetas de árboles */
        .tree-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.2s;
        }

        .tree-card:hover {
            transform: translateY(-5px);
        }

        .tree-info {
            margin-bottom: 15px;
        }

        .tree-name {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 1.2em;
        }

        .tree-content {
            margin: 15px 0;
        }

        .tree-description {
            color: #7f8c8d;
            margin: 10px 0;
            line-height: 1.5;
        }

        /* Botones de acción */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .view-btn {
            background-color: #3498db;
            color: white;
        }

        .view-btn:hover {
            background-color: #2980b9;
        }

        .buy-btn {
            background-color: #2ecc71;
            color: white;
        }

        .buy-btn:hover {
            background-color: #27ae60;
        }

        /* Estilos para la imagen del árbol */
        .tree-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        /* Detalles del árbol */
        .tree-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-weight: 600;
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .detail-value {
            color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Bienvenido <?php echo htmlspecialchars($user['name']); ?></h1>
            <hr>
            <h2>Árboles Disponibles</h2>
        </div>
    <div class="trees-container">
            <?php foreach ($getAvailableTrees as $tree): ?>
                <div class="tree-card">
                    <?php echo $tree['photo_url']; ?>
                    <img src="<?php echo htmlspecialchars($tree['photo_url']); ?>" alt="Árbol" class="tree-image">
                    <div class="tree-info">
                        <span class="tree-name"><?php echo htmlspecialchars($tree['commercial_name']); ?></span>
                        <div class="tree-details">
                            <div class="detail-item">
                                <span class="detail-label">ID</span>
                                <span class="detail-value"><?php echo htmlspecialchars($tree['id']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">OwnerId</span>
                                <span class="detail-value"><?php echo htmlspecialchars($tree['owner_id']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Altura</span>
                                <span class="detail-value"><?php echo htmlspecialchars($tree['height']); ?> metros</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Ubicación</span>
                                <span class="detail-value"><?php echo htmlspecialchars($tree['location']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Precio</span>
                                <span class="detail-value">$<?php echo htmlspecialchars($tree['price']); ?></span>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <form method="POST" action="">
                                <input type="hidden" name="tree_id" value="<?php echo htmlspecialchars($tree['id']); ?>">
                                <button type="submit" name="action" value="buy_tree" class="action-button buy-btn">Comprar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="dashboard-header">
            <h2>Árboles Comprados</h2>
        </div>
        <div class="trees-container">
            <?php foreach ($getPurchasedTrees as $pTree): ?>
                <div class="tree-card">
                    <img src="<?php echo htmlspecialchars($ptree['photo_url']); ?>" alt="Árbol" class="tree-image">
                    <div class="tree-info">
                        <span class="tree-name"><?php echo htmlspecialchars($pTree['commercial_name']); ?></span>
                        <div class="tree-details">
                            <div class="detail-item">
                                <span class="detail-label">ID</span>
                                <span class="detail-value"><?php echo htmlspecialchars($pTree['id']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">OwnerId</span>
                                <span class="detail-value"><?php echo htmlspecialchars($pTree['owner_id']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Altura</span>
                                <span class="detail-value"><?php echo htmlspecialchars($pTree['height']); ?> metros</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Ubicación</span>
                                <span class="detail-value"><?php echo htmlspecialchars($pTree['location']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Precio</span>
                                <span class="detail-value">$<?php echo htmlspecialchars($pTree['price']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>