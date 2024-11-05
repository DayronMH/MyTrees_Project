<?php

require_once '../models/treesModel.php';

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
            $treesModel = new TreesModel();
            $success = $treesModel->buyTree($treeId, $userId);
    
            if ($success) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            } else {
                echo "Error: Failed to buy the tree.";
            }
        } else {
            echo "Error: Invalid data received.";
        }
    }
}
$treeController = new TreeController();