<?php
session_start();
require_once '../controllers/friendDashboardController.php';
require_once '../controllers/salesController.php';

$friendDashboardController = new FriendDashboardController();
$salesController = new SalesController();
$availableTrees = $friendDashboardController ->getAvailableTrees();

// Verificar si el ID del usuario está disponible
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$purchasedTrees = [];

//try

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Debes iniciar sesión para comprar un árbol.'); window.location.href = '../login.php';</script>";
    exit;
}

if (isset($_POST['tree_id'])) {
    $userId = $_SESSION['user_id'];
    $treeId = $_POST['tree_id'];

    // Llamar al controlador para manejar la compra
    $result = $salesController->createSale($userId, $treeId);

    if ($result) {
        echo "<script>alert('Árbol comprado exitosamente.'); window.location.href = 'friendDashboard.php';</script>";
    } else {
        echo "<script>alert('Error al comprar el árbol. Intenta nuevamente.'); window.location.href = 'friendDashboard.php';</script>";
    }
} else {
    echo "<script>alert('No se ha proporcionado un ID de árbol válido.'); window.location.href = 'friendDashboard.php';</script>";
}

//Try


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://mytrees.com/public/friend.css">
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
            <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <hr>
            <h2>Árboles Disponibles</h2>
        </div>

        <div class="trees-container">
        <?php foreach ($availableTrees as $tree): ?>
            <div class="tree-card">
                <img src="<?php echo htmlspecialchars($tree['photo_url']); ?>" alt="Árbol" class="tree-image">
                <div class="tree-info">
                    <span class="tree-name"><?php echo htmlspecialchars($tree['species']); ?></span>
                    <div class="tree-details">
                        <div class="detail-item">
                            <span class="detail-label">ID</span>
                            <span class="detail-value"><?php echo htmlspecialchars($tree['id']); ?></span>
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
                        <form method="POST" action="comprar.php">
                            <!-- Aquí se asegura que el ID del árbol se pasa correctamente -->
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
            <hr>
            <div class="trees-container">
                <?php if (empty($purchasedTrees)): ?>
                    <p>No has comprado ningún árbol todavía.</p>
                <?php else: ?>
                    <?php foreach ($purchasedTrees as $purchasedTree): ?>
                        <div class="tree-card">
                            <img src="<?php echo htmlspecialchars($purchasedTree['photo_url']); ?>" alt="Árbol Comprado" class="tree-image">
                            <div class="tree-info">
                                <span class="tree-name"><?php echo htmlspecialchars($purchasedTree['species']); ?></span>
                                <div class="tree-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Altura</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($purchasedTree['height']); ?> metros</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Ubicación</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($purchasedTree['location']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Precio</span>
                                        <span class="detail-value">$<?php echo htmlspecialchars($purchasedTree['price']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<script src="http://mytrees.com/public/js/modal.js"></script>
</html>