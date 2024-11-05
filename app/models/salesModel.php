<?php
require_once 'baseModel.php';

class SalesModel extends BaseModel
{
    
    public function __construct()
    {
        parent::__construct('Sales');
    }

    public function createSale(int $buyerId, int $treeId): bool
    {
        $query = "INSERT INTO `Sales` (`tree_id`, `buyer_id`) VALUES (:tree_id, :buyer_id)";
        return $this->executeQuery($query, [':tree_id' => $treeId, ':buyer_id' => $buyerId]);
    }

    public function getSalesByBuyerId(int $buyerId): array
    {
        $query = "SELECT * FROM `sales` WHERE `buyer_id` = :buyer_id";
        return $this->fetchRecords($query, [':buyer_id' => $buyerId]);
    }
 
    public function getSalesByTreeId(int $treeId): array
    {
        $query = "SELECT * FROM `Sales` WHERE `tree_id` = :tree_id";
        return $this->fetchRecords($query, [':tree_id' => $treeId]);
    }

    private function fetchRecords(string $query, array $params)
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    private function executeQuery(string $query, array $params): bool
    {
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function isTreeAvailable($treeId) {
        $query = "SELECT available FROM Trees WHERE id = :treeId";
        return $this->fetchRecords($query, [':tree_id' => $treeId]);
    }
    
   public function registerSale($userId, $treeId) {
        $query = "INSERT INTO Sales (tree_id, buyer_id) VALUES (:treeId, :userId)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            ':treeId' => $treeId,
            ':userId' => $userId
        ]);
    }
    
    public function markTreeAsSold($treeId) {
        $query = "UPDATE Trees SET available = FALSE WHERE id = :treeId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':treeId' => $treeId]);
    }

}