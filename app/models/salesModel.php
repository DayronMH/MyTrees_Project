<?php
require_once 'baseModel.php';

class SalesModel extends BaseModel
{
    
    public function __construct()
    {
        parent::__construct('Sales');
    }

    /**
     * Creates a sale record in the database.
     *
     * @param int $treeId The ID of the tree.
     * @param int $buyerId The ID of the buyer.
     * @return bool Returns true on success, false on failure.
     */
    public function createSale(int $buyerId, int $tree): bool
    {
        $query = "INSERT INTO `Sales` (`tree_id`, `buyer_id`) VALUES (:tree_id, :buyer_id)";
        return $this->executeQuery($query, [':tree_id' => $treeId, ':buyer_id' => $buyerId]);
    }

    /**
     * Retrieves sales records by buyer ID.
     *
     * @param int $buyerId The ID of the buyer.
     * @return array Returns an array of sales records.
     */
    public function getSalesByBuyerId(int $buyerId): array
    {
        $query = "SELECT * FROM `sales` WHERE `buyer_id` = :buyer_id";
        return $this->fetchRecords($query, [':buyer_id' => $buyerId]);
    }
    public function getPurchasedTree(int $buyerId): array
    {
        $query = "SELECT * FROM `trees` WHERE `available` = :available";
        return $this->fetchRecords($query, [':buyer_id' => $buyerId]);
    }

    /**
     * Retrieves sales records by tree ID.
     *
     * @param int $treeId The ID of the tree.
     * @return array Returns an array of sales records.
     */
    public function getSalesByTreeId(int $treeId): array
    {
        $query = "SELECT * FROM `Sales` WHERE `tree_id` = :tree_id";
        return $this->fetchRecords($query, [':tree_id' => $treeId]);
    }

    /**
     * Executes a query and returns the results.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array|bool Returns fetched records or false on failure.
     */
    private function fetchRecords(string $query, array $params)
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error or handle accordingly
            return false;
        }
    }

    /**
     * Executes a query for actions like INSERT, UPDATE, DELETE.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return bool Returns true on success, false on failure.
     */
    private function executeQuery(string $query, array $params): bool
    {
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            // Log error or handle accordingly
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