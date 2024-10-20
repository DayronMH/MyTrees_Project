<?php
require_once 'databaseModel.php';

class TreesModel extends BaseModel
{
    // Method to create a new tree without owner, height, or available, as they default to admin: 1ft height and FALSE
    public function createTreeBasic($species_id, $location, $price, $photo_url)
    {
        $query = "INSERT INTO `trees` (`species_id`, `location`, `price`, `photo_url`)
                  VALUES (:species_id, :location, :price, :photo_url)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':species_id', $species_id);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':photo_url', $photo_url);

        return $stmt->execute();
    }

    // Method to create a new tree with all fields
    public function createTree($species_id, $owner_id, $height, $location, $available, $price, $photo_url)
    {
        $query = "INSERT INTO `trees` (`species_id`, `owner_id`, `height`, `location`, `available`, `price`, `photo_url`)
                  VALUES (:species_id, :owner_id, :height, :location, :available, :price, :photo_url)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':species_id', $species_id);
        $stmt->bindParam(':owner_id', $owner_id);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':available', $available);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':photo_url', $photo_url);

        return $stmt->execute();
    }

    // Method to update a specific tree by ID
    public function updateTree($id, $species_id, $owner_id, $height, $location, $available, $price, $photo_url)
    {
        $query = "UPDATE `trees` 
                  SET `species_id` = :species_id, `owner_id` = :owner_id, `height` = :height, `location` = :location, 
                      `available` = :available, `price` = :price, `photo_url` = :photo_url
                  WHERE `id` = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':species_id', $species_id);
        $stmt->bindParam(':owner_id', $owner_id);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':available', $available);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':photo_url', $photo_url);

        return $stmt->execute();
    }

    // Method to get all available trees
    public function getAvailableTrees()
    {
        $query = "SELECT * FROM Trees WHERE available = TRUE ORDER BY price ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to filter trees by species
    public function getTreesBySpecies($species_id)
    {
        $query = "SELECT * FROM Trees WHERE species_id = :species_id ORDER BY height DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':species_id', $species_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get information about a tree by ID
    public function getTreeById($id)
    {
        $query = "SELECT t.id, t.height, t.location, t.price, t.photo_url, t.available, s.commercial_name, s.scientific_name, u.name AS owner_name 
                  FROM Trees t 
                  JOIN Species s ON t.species_id = s.id 
                  LEFT JOIN Users u ON t.owner_id = u.id 
                  WHERE t.id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to get information about all trees
    public function getTrees()
    {
        $query = "SELECT * FROM `trees`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
// Method to delete a tree by its ID
    public function deleteTree($id)
    {
        $query = "DELETE FROM `trees` WHERE `id` = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
?>