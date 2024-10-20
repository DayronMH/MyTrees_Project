<?php
require_once 'databaseModel.php';

class treesModel extends BaseModel
{
    public function addNewTree($species_id, $owner_id, $size, $location, $status, $price, $photo_url, $availability_date)
    {
        $query = "INSERT INTO `trees`( 'species_id', 'owner_id', 'size', 'location', 'status', 'price', 'photo_url', 'availability_date') 
                  VALUES ( :species_id, :owner_id, :size, :location, :status, :price, :photo_url, :availability_date)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':species_id', $species_id,);
        $stmt->bindParam(':owner_id',$owner_id);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':photo_url', $photo_url);
        $stmt->bindParam(':availability_date', $availability_date);
        return $stmt->execute();
    }
    
}

