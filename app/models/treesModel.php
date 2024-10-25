<?php
require_once 'baseModel.php';


class TreesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct('trees');
    }
    /**
     * Creates a new tree with default values for owner, height, and availability.
     *
     * @param int $species_id The ID of the species.
     * @param string $location The location of the tree.
     * @param float $price The price of the tree.
     * @param string $photo_url The URL of the tree's photo.
     * @return bool Returns true on success, false on failure.
     */
    public function getTreesByUserId($userId)
    {
        $query = "SELECT * FROM `trees` WHERE `owner_id` = :owner_id";
        return $this->executeQuery($query, [':owner_id' => $userId]);
    }
    public function createTreeBasic(int $species_id, string $location, float $price, string $photo_url): bool
    {
        $query = "INSERT INTO `trees` (`species_id`, `location`, `price`, `photo_url`)
                  VALUES (:species_id, :location, :price, :photo_url)";
        return $this->executeQuery($query, [
            ':species_id' => $species_id,
            ':location' => $location,
            ':price' => $price,
            ':photo_url' => $photo_url
        ]);
    }

    /**
     * Creates a new tree with all specified fields.
     *
     * @param int $species_id The ID of the species.
     * @param int $owner_id The ID of the owner.
     * @param float $height The height of the tree.
     * @param string $location The location of the tree.
     * @param bool $available Availability status of the tree.
     * @param float $price The price of the tree.
     * @param string $photo_url The URL of the tree's photo.
     * @return bool Returns true on success, false on failure.
     */
    public function createTree(int $species_id, int $owner_id, float $height, string $location, bool $available, float $price, string $photo_url): bool
    {
        $query = "INSERT INTO `trees` (`species_id`, `owner_id`, `height`, `location`, `available`, `price`, `photo_url`)
                  VALUES (:species_id, :owner_id, :height, :location, :available, :price, :photo_url)";
        return $this->executeQuery($query, [
            ':species_id' => $species_id,
            ':owner_id' => $owner_id,
            ':height' => $height,
            ':location' => $location,
            ':available' => $available,
            ':price' => $price,
            ':photo_url' => $photo_url
        ]);
    }

    /**
     * Updates a specific tree by ID.
     *
     * @param int $id The ID of the tree.
     * @param int $species_id The ID of the species.
     * @param int $owner_id The ID of the owner.
     * @param float $height The height of the tree.
     * @param string $location The location of the tree.
     * @param bool $available Availability status of the tree.
     * @param float $price The price of the tree.
     * @param string $photo_url The URL of the tree's photo.
     * @return bool Returns true on success, false on failure.
     */
    public function updateTree(int $id, int $species_id, int $owner_id, float $height, string $location, bool $available, float $price, string $photo_url): bool
    {
        $query = "UPDATE `trees` 
                  SET `species_id` = :species_id, `owner_id` = :owner_id, `height` = :height, 
                      `location` = :location, `available` = :available, `price` = :price, 
                      `photo_url` = :photo_url
                  WHERE `id` = :id";
        return $this->executeQuery($query, [
            ':id' => $id,
            ':species_id' => $species_id,
            ':owner_id' => $owner_id,
            ':height' => $height,
            ':location' => $location,
            ':available' => $available,
            ':price' => $price,
            ':photo_url' => $photo_url
        ]);
    }

    /**
     * Retrieves all available trees.
     *
 * @return array Returns an array of available trees.
     */
    public function getAvailableTrees(): array
    {
        $query = "SELECT * FROM Trees WHERE available = TRUE ORDER BY price ASC";
        return $this->executeQuery($query);
    }

    /**
     * Retrieves trees by species.
     *
     * @param int $species_id The ID of the species.
     * @return array Returns an array of trees by species.
     */
    public function getTreesBySpecies(int $species_id): array
    {
        $query = "SELECT * FROM Trees WHERE species_id = :species_id ORDER BY height DESC";
        return $this->executeQuery($query, [':species_id' => $species_id]);
    }

    /**
     * Retrieves information about a tree by ID.
     *
     * @param int $id The ID of the tree.
     * @return array Returns an array of tree information.
     */
    public function getTreeById(int $id): array
    {
        $query = "SELECT t.id, t.height, t.location, t.price, t.photo_url, t.available, s.commercial_name, s.scientific_name, u.name AS owner_name 
                  FROM Trees t 
                  JOIN Species s ON t.species_id = s.id 
                  LEFT JOIN Users u ON t.owner_id = u.id 
                  WHERE t.id = :id";
        return $this->executeQuery($query, [':id' => $id]);
    }

    /**
     * Retrieves information about all trees.
     *
     * @return array Returns an array of all trees.
     */
    public function getTrees(): array
    {
        $query = "SELECT * FROM `trees`";
        return $this->executeQuery($query);
    }

    /**
     * Deletes a tree by its ID.
     *
     * @param int $id The ID of the tree.
     * @return bool Returns true on success, false on failure.
     */
    public function deleteTree(int $id): bool
    {
        $query = "DELETE FROM `trees` WHERE `id` = :id";
        return $this->executeQuery($query, [':id' => $id]);
    }
    public function countAvailableTrees(): int {
        $query = "SELECT COUNT(*) as count FROM `trees` WHERE `available` = 1";
        
        // Ejecutar la consulta
        $result = $this->executeQuery($query);
        
        // Asegurarte de que estás retornando solo el valor del conteo
        return (int)$result[0]['count'];
    }
    
    public function countSoldTrees(): int {
        $query = "SELECT COUNT(*) as count FROM `trees` WHERE `available` = 0";
        
        // Ejecutar la consulta
        $result = $this->executeQuery($query);
        
        // Asegurarte de que estás retornando solo el valor del conteo
        return (int)$result[0]['count'];
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