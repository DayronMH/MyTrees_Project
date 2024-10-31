<?php
require_once 'SalesModel.php';
require_once 'TreesModel.php';

class SalesController
{
    private $salesModel;
    private $treesModel;

    public function __construct()
    {
        $this->salesModel = new SalesModel();
        $this->treesModel = new TreesModel();
    }

    /**
     * Handles the sale creation process.
     *
     * @param int $treeId The ID of the tree being sold.
     * @param int $buyerId The ID of the buyer.
     * @return array Returns an array with status and message.
     */
    public function createSale(int $treeId, int $buyerId): array
    {
        // Check if the tree is available for sale
        $tree = $this->treesModel->getTreeById($treeId);
        
        if (empty($tree)) {
            return ['status' => 'error', 'message' => 'Tree not found.'];
        }
        
        if (!$tree[0]['available']) {
            return ['status' => 'error', 'message' => 'Tree is not available for sale.'];
        }

        // Create the sale record
        if ($this->salesModel->createSale($treeId, $buyerId)) {
            // Update tree availability after successful sale
            $this->treesModel->editTree($treeId, $tree[0]['height'], $tree[0]['location'], false);
            return ['status' => 'success', 'message' => 'Sale created successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to create sale.'];
        }
    }

    /**
     * Retrieves sales records for a specific buyer.
     *
     * @param int $buyerId The ID of the buyer.
     * @return array Returns an array of sales records.
     */
    public function getSalesByBuyer(int $buyerId): array
    {
        return $this->salesModel->getSalesByBuyerId($buyerId);
    }

    /**
     * Retrieves sales records for a specific tree.
     *
     * @param int $treeId The ID of the tree.
     * @return array Returns an array of sales records.
     */
    public function getSalesByTree(int $treeId): array
    {
        return $this->salesModel->getSalesByTreeId($treeId);
    }

    /**
     * Retrieves purchased trees by a user.
     *
     * @param int $userId The ID of the user.
     * @return array Returns an array of purchased trees.
     */
    public function getPurchasedTreesByUser(int $userId): array
    {
        return $this->treesModel->getPurchasedTreesByUser($userId);
    }
}