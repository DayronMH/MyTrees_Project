<?php
require_once 'databaseModel.php';
class TreeUpdatesModel extends BaseModel
{
    public function createTreeUpdate($tree_id, $size, $height, $growth_rate, $health_status, $image_url, $status)
    {
        $query = "INSERT INTO `tree_updates`( `tree_id`, `size`, `height`, `growth_rate`, `health_status`, `image_url`, `status`) 
                  VALUES ( :tree_id, :size, :height, :growth_rate, :health_status, :image_url, :status)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tree_id', $tree_id);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':growth_rate', $growth_rate);
        $stmt->bindParam(':health_status', $health_status);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function getTreeUpdatesByTreeId($tree_id)
    {
        $query = "SELECT * FROM `tree_updates` WHERE `tree_id` = :tree_id ORDER BY `update_date` DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tree_id', $tree_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestTreeUpdate($tree_id)
    {
        $query = "SELECT * FROM `tree_updates` WHERE `tree_id` = :tree_id ORDER BY `update_date` DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tree_id', $tree_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>