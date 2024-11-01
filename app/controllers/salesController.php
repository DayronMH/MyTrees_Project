<?php

require_once '../models/salesModel.php';
require_once '../models/treesModel.php';

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
    public function createSale(int $buyerId, int $treeId): array
    {
        if ($this->salesModel->createSale($buyerId, $treeId)) {
            $this->salesModel->markTreeAsSold($treeId);
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