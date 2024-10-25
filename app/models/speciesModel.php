<?php
require_once 'baseModel.php';

class speciesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct('species');
    }
    /**
     * Retrieves all species from the database.
     *
     * @return array Returns an array of species records.
     */
    public function getAllSpecies(): array
    {
        $query = "SELECT id, commercial_name, scientific_name FROM Species";
        return $this->executeQuery($query);
    }

    /**
     * Retrieves a species by its ID.
     *
     * @param int $speciesId The ID of the species.
     * @return array|bool Returns the species record as an associative array, or false on failure.
     */
    public function getSpeciesById(int $speciesId)
    {
        $query = "SELECT id, commercial_name, scientific_name FROM Species WHERE id = :species_id";
        return $this->executeQuery($query, [':species_id' => $speciesId]);
    }
    public function getCommercialNames()
    {
        $query = "SELECT id, commercial_name FROM Species";
        return $this->executeQuery($query);
    }
    public function getScientificNames()
    {
        $query = "SELECT id, scientific_name FROM Species ";
        return $this->executeQuery($query);
    }

    public function deleteSpecies($id)
    {
        $query = "DELETE FROM Species WHERE id = ?";
        $this->executeQuery($query, [$id]);
    }


    /**
     * Executes a query and returns results.
     *
     * @param string $query The SQL query to execute.
     * @param array|null $params The parameters to bind to the query, if any.
     * @return array|bool Returns fetched records or false on failure.
     */
    private function executeQuery(string $query, array $params = null)
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params ?? []);
            return $params ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error or handle accordingly
            return false;
        }
    }
}
