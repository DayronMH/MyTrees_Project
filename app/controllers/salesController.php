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

    public function createSale(int $buyerId, int $treeId): array
    {
        if ($this->salesModel->createSale($buyerId, $treeId)) {
            $this->salesModel->markTreeAsSold($treeId);
            return ['status' => 'success', 'message' => 'Sale created successfully.'];

        } else {
            return ['status' => 'error', 'message' => 'Failed to create sale.'];
        }
    }

    public function getSalesByBuyer(int $buyerId): array
    {
        return $this->salesModel->getSalesByBuyerId($buyerId);
    }

    public function getSalesByTree(int $treeId): array
    {
        return $this->salesModel->getSalesByTreeId($treeId);
    }

    public function getPurchasedTreesByUser(int $userId): array
    {
        return $this->treesModel->getPurchasedTreesByUser($userId);
    }
}