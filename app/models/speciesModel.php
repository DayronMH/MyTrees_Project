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
        $query = "SELECT id, commercial_name, scientific_name FROM species"; // Asegúrate de que el nombre de la tabla sea correcto.
        return $this->executeQuery($query);
    }
    public function getCommercialNames(): array
    {
        $query = "SELECT id, commercial_name FROM species";
        return $this->executeQuery($query);
    }
    public function getScientificNames(): array
    {
        $query = "SELECT scientific_name FROM species";
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
        $query = "SELECT id, commercial_name, scientific_name FROM species WHERE id = :species_id";
        return $this->executeQuery($query, [':species_id' => $speciesId]);
    }

    /**
     * Creates a new species in the database.
     *
     * @param string $commercialName The commercial name of the species.
     * @param string $scientificName The scientific name of the species.
     * @return bool Returns true on success or false on failure.
     */
    public function createSpecies(string $commercialName, string $scientificName): bool
    {
        $query = "INSERT INTO species (commercial_name, scientific_name) VALUES (:commercial_name, :scientific_name)";
        return $this->executeQuery($query, [
            ':commercial_name' => $commercialName,
            ':scientific_name' => $scientificName
        ]);
    }

    /**
     * Updates a species in the database.
     *
     * @param int $speciesId The ID of the species to update.
     * @param string $commercialName The new commercial name of the species.
     * @param string $scientificName The new scientific name of the species.
     * @return bool Returns true on success or false on failure.
     */
    public function updateSpecies(int $speciesId, string $commercialName, string $scientificName): bool
    {
        $query = "UPDATE species SET commercial_name = :commercial_name, scientific_name = :scientific_name WHERE id = :species_id";
        return $this->executeQuery($query, [
            ':species_id' => $speciesId,
            ':commercial_name' => $commercialName,
            ':scientific_name' => $scientificName
        ]);
    }

    /**
     * Deletes a species from the database.
     *
     * @param int $speciesId The ID of the species to delete.
     * @return bool Returns true on success or false on failure.
     */
    public function deleteSpecies(int $speciesId): bool
    {
        $query = "DELETE FROM species WHERE id = :species_id";
        return $this->executeQuery($query, [':species_id' => $speciesId]);
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
