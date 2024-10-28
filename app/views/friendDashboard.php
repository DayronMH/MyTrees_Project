<?php
session_start();
require_once '../controllers/friendDashboardController.php';
$data = new FriendDashboardController();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <?php
            $trees = $_SESSION['tree_species'];
            $heights = $_SESSION['tree_heights'];
            $locations = $_SESSION['tree_locations'];
            $prices = $_SESSION['tree_prices'];
            $photos = $_SESSION['tree_photos'];

            for ($i = 0; $i < count($trees); $i++) {
                $treeId = $i + 1; // Asumiendo que necesitamos un ID único
            ?>
                <div class="tree-card">
                    <img src="<?php echo htmlspecialchars($photos[$i]); ?>" alt="Árbol" class="tree-image">
                    <div class="tree-info">
                        <h3 class="tree-name"><?php echo htmlspecialchars($trees[$i]); ?></h3>
                        
                        <div class="tree-details">
                            <div class="detail-item">
                                <span class="detail-label">Altura</span>
                                <span class="detail-value"><?php echo htmlspecialchars($heights[$i]); ?> metros</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Ubicación</span>
                                <span class="detail-value"><?php echo htmlspecialchars($locations[$i]); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Precio</span>
                                <span class="detail-value">$<?php echo htmlspecialchars($prices[$i]); ?></span>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <form method="POST" action="" style="display: flex; gap: 10px;">
                                <input type="hidden" name="tree_id" value="<?php echo $treeId; ?>">
                                <button type="submit" name="action" value="view_details" class="action-button view-btn">
                                    Ver Detalles
                                </button>
                                <button type="submit" name="action" value="buy_tree" class="action-button buy-btn">
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
<script src="http://mytrees.com/public/js/modal.js"></script>
</html>