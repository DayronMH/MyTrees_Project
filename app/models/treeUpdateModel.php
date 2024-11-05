<?php
require_once 'baseModel.php';

class treesUpdatesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct('tree_updates');
    }

    public function createTreeUpdate( $tree_id, $height, $image_url,$status, $updateDate): bool
    {
        $query = "INSERT INTO `tree_updates` (`tree_id`, `height`, `image_url`, `status`, `update_date`) 
                  VALUES (:tree_id,:height, :image_url, :status, :updateDate)";
        return $this->executeNonQuery($query, [
            ':tree_id' => $tree_id,
            ':height' => $height,
            ':image_url' => $image_url,
            ':status' => $status,
            ':updateDate' => $updateDate,
        ]);
    }
    private function executeNonQuery(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
    
    public function getTreeUpdatesByTreeId(int $tree_id): array
    {
        $query = "SELECT * FROM `tree_updates` WHERE `tree_id` = :tree_id ORDER BY `update_date` DESC";
        return $this->executeQuery($query, [':tree_id' => $tree_id]);
    }

    public function getLatestTreeUpdate(int $tree_id): ?array
    {
        $query = "SELECT * FROM `tree_updates` WHERE `tree_id` = :tree_id ORDER BY `update_date` DESC LIMIT 1";
        $result = $this->executeQuery($query, [':tree_id' => $tree_id]);
        return !empty($result) ? $result[0] : null;
    }

    public function getOutdatedTrees(): array
    {
        $query = "SELECT * FROM trees WHERE 'update_date' < DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        return $this->executeQuery($query);
    }
    
    private function executeQuery(string $query, array $params = []): mixed
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
}