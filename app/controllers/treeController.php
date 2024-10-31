<?php

require_once '../models/treesModel.php'; // Assuming treeModel.php handles tree data access

class TreeController {

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action'])) {
                $action = $_POST['action'];
                switch ($action) {
                    case 'buy_tree':
                        $this->buyTree();
                        break;
                    default:
                        // Handle unknown actions
                        break;
                }
            }
        }
    }

    public function buyTree() {
        $treeId = filter_input(INPUT_POST, 'tree_id', FILTER_VALIDATE_INT);
        $userId = $_SESSION['user_id'];
        $success = null;

        if ($treeId && $userId) {
            $treeModel = new TreeModel();
    
            // Update the tree with the user ID and available status
            $success = $treeModel->updateTree($treeId, $userId, false);
    
            if ($success) {
                // Redirect to success page or reload current page
                header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirect to previous page
                exit();
            } else {
                // Handle update failure (e.g., display error message)
                echo "Error: Failed to buy the tree."; // Placeholder error message
            }
        } else {
            // Handle invalid data (e.g., missing ID)
            echo "Error: Invalid data received."; // Placeholder error message
        }
    }
}

// Create an instance of the TreeController
$treeController = new TreeController();