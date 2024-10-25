<?php
require_once 'baseModel.php';

class treeUpdatesModel extends BaseModel
{
    /**
     * Creates a new tree update record.
     *
     * @param int $tree_id The ID of the tree.
     * @param float $size The size of the tree.
     * @param float $height The height of the tree.
     * @param float $growth_rate The growth rate of the tree.
     * @param string $health_status The health status of the tree.
     * @param string $image_url The URL of the tree's image.
     * @param string $status The status of the update.
     * @return bool Returns true on success, false on failure.
     */
    public function createTreeUpdate(int $tree_id, float $size, float $height, float $growth_rate, string $health_status, string $image_url, string $status): bool
    {
        $query = "INSERT INTO `tree_updates` (`tree_id`, `size`, `height`, `growth_rate`, `health_status`, `image_url`, `status`) 
                  VALUES (:tree_id, :size, :height, :growth_rate, :health_status, :image_url, :status)";
        return $this->executeQuery($query, [
            ':tree_id' => $tree_id,
            ':size' => $size,
            ':height' => $height,
            ':growth_rate' => $growth_rate,
            ':health_status' => $health_status,
            ':image_url' => $image_url,
            ':status' => $status
        ]);
    }

    /**
     * Retrieves all updates for a specific tree by its ID.
     *
     * @param int $tree_id The ID of the tree.
     * @return array Returns an array of tree updates.
     */
    public function getTreeUpdatesByTreeId(int $tree_id): array
    {
        $query = "SELECT * FROM `tree_updates` WHERE `tree_id` = :tree_id ORDER BY `update_date` DESC";
        return $this->executeQuery($query, [':tree_id' => $tree_id]);
    }

    /**
     * Retrieves the latest update for a specific tree by its ID.
     *
     * @param int $tree_id The ID of the tree.
     * @return array|null Returns the latest tree update or null if not found.
     */
    public function getLatestTreeUpdate(int $tree_id): ?array
    {
        $query = "SELECT * FROM `tree_updates` WHERE `tree_id` = :tree_id ORDER BY `update_date` DESC LIMIT 1";
        $result = $this->executeQuery($query, [':tree_id' => $tree_id]);
        return !empty($result) ? $result[0] : null; // Return the first result or null if empty
    }

    /**
     * Executes a query and fetches the results.
     *
     * @param string $query The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return mixed Returns the result of the query execution.
     */
    private function executeQuery(string $query, array $params = []): mixed
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the error, log it, or rethrow it
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
}