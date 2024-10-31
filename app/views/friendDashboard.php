<?php
session_start();
require_once '../controllers/friendDashboardController.php';
require_once '../Models/treesModel.php';
require_once '../controllers/salesController.php';
require_once 'targetPage.php';

$friendDashboardController = new FriendDashboardController();
$salesController = new SalesController();
$treesController = new treesModel();
$getAvailableTrees = $friendDashboardController->getAvailableTrees();

// Verificar si el ID del usuario está disponible
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$purchasedTrees = [];

//try

if (!isset($_SESSION['user_id'])) {

    $_SESSION['error']=( "Debes iniciar sesión para comprar un árbol.");
    header('Location: ../login.php');
    exit;
}

if (isset($_POST['tree_id'])) {
    $userId = $_SESSION['user_id'];
    $treeId = $_POST['tree_id'];

    // Llamar al controlador para manejar la compra
    $result = $salesController->createSale($userId, $treeId);

    if ($result) {
        $_SESSION['success']=("Árbol comprado exitosamente.");
        header('Location: friendDashboard.php');
        exit;
    } else {
        $_SESSION['error' ] = ("fallonazo.");
        header('Location: friendDashboard.php');
        exit;
    }
} else {
    echo "<script>alert('No se ha proporcionado un ID de árbol válido.'); window.location.href = 'friendDashboard.php';</script>";
}

// Fetch purchased trees if needed (assuming you have a method to get them)
$purchasedTrees = $treesController->getPurchasedTreesByUser($userId); // Implement this method in your SalesController

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://mytrees.com/public/friend.css">
    <title>Amigo - Panel de Control</title>
</head>

<body>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Bienvenido <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <hr>
            <h2>Árboles Disponibles</h2>
        </div>
        <div class="trees-container">
            <?php foreach ($getAvailableTrees as $tree):
               ?>

                <div class="tree-card">
                    <img src="<?php echo htmlspecialchars($tree['photo_url']); ?>" alt="Árbol" class="tree-image">
                    <div class="tree-info">
                        <span class="tree-name"><?php echo htmlspecialchars($tree['commercial_name']); ?></span>
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
                            <form method="POST" action="">
                                <input type="hidden" name="tree_id" value="<?php echo htmlspecialchars($tree['id']); ?>">
                                <button type="submit" name="action" value="buy_tree" class="action-button buy-btn">Comprar</button>
                                <?php 
                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'buy_tree' && isset($_POST['tree_id'])) {
                                    $treeId = $_POST['tree_id'];
                                    
                                    // Intentar realizar la compra usando el controlador de ventas
                                    $result = $salesController->createSale($userId, $treeId);
                                
                                    if ($result) {
                                        $_SESSION['success'] = "Árbol comprado exitosamente.";
                                    } else {
                                        $_SESSION['error'] = "Ocurrió un problema al intentar comprar el árbol.";
                                    }
                                }
                                ?>
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
                <?php if (empty($purchasedTrees)) : ?>
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
</html>