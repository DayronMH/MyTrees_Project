<?php
require_once 'databaseModel.php';
class SalesModel extends BaseModel
{
    public function createSale($tree_id, $buyer_id)
    {
        $query = "INSERT INTO `sales`( `tree_id`, `buyer_id`) 
                  VALUES ( :tree_id, :buyer_id)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tree_id', $tree_id);
        $stmt->bindParam(':buyer_id', $buyer_id);
        return $stmt->execute();
    }

    public function getSalesByBuyerId($buyer_id)
    {
        $query = "SELECT * FROM `sales` WHERE `buyer_id` = :buyer_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':buyer_id', $buyer_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalesByTreeId($tree_id)
    {
        $query = "SELECT * FROM `sales` WHERE `tree_id` = :tree_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tree_id', $tree_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>