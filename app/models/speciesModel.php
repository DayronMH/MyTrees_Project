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
    public function createSpecie(string $commercial_name, string $scientific_name): bool
    {
        date_default_timezone_set('America/Costa_Rica'); // Cambia la zona horaria según tu ubicación

// Obtener la fecha y hora actual
        $date = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO `species` (`commercial_name`, `scientific_name`, `availability_date`)
                  VALUES (:commercial_name, :scientific_name, :availability_date)";

        return $this->executeNonQuery($query, [
            ':commercial_name' => $commercial_name,
            ':scientific_name' => $scientific_name,
            ':availability_date' => $date
        ]);
    }

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

    public function deleteSpecie(int $id): bool
    {

        $deleteRelatedTreesQuery = "DELETE FROM `trees` WHERE `species_id` = :species_id";
        $relatedTreesDeleted = $this->executeQuery($deleteRelatedTreesQuery, [':species_id' => $id]);

        // Si los árboles relacionados fueron eliminados correctamente, proceder a eliminar la especie
        if ($relatedTreesDeleted) {
            $deleteSpeciesQuery = "DELETE FROM `species` WHERE `id` = :id";
            return $this->executeQuery($deleteSpeciesQuery, [':id' => $id]);
        }

        return false;
    }
    public function hasTreesAssociated($speciesId): bool
{
    $query = "SELECT COUNT(*) FROM `trees` WHERE `species_id` = :species_id";
    $stmt = $this->db->prepare($query);
    $stmt->execute([':species_id' => $speciesId]);
    return $stmt->fetchColumn() > 0; // Devuelve true si hay árboles asociados
}

    public function editSpecies($speciesId, $commercialName, $scientificName)
    {
        $stmt = $this->db->prepare("UPDATE species SET commercial_name = ?, scientific_name = ? WHERE id = ?");
        return $stmt->execute([$commercialName, $scientificName, $speciesId]);
    }
    private function executeNonQuery(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            // Handle the error, log it, or rethrow it
            throw new Exception("Database query failed: " . $e->getMessage());
        }
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
