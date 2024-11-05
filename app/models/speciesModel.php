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

    public function deleteSpecie(int $id): array
{
    try {
        // Primero verificamos si hay árboles asociados
        if ($this->hasTreesAssociated($id)) {
            // Si hay árboles asociados, intentamos eliminarlos primero
            $deleteRelatedTreesQuery = "DELETE FROM `trees` WHERE `species_id` = :species_id";
            $relatedTreesDeleted = $this->executeQuery($deleteRelatedTreesQuery, [':species_id' => $id]);
            
            if (!$relatedTreesDeleted) {
                return [
                    'error' => 'No se pudieron eliminar los árboles asociados a esta especie'
                ];
            }
        }
        
        // Una vez que no hay árboles (ya sea porque se eliminaron o porque no había),
        // procedemos a eliminar la especie
        $deleteSpeciesQuery = "DELETE FROM `species` WHERE `id` = :id";
        $speciesDeleted = $this->executeQuery($deleteSpeciesQuery, [':id' => $id]);
        
        if ($speciesDeleted) {
            return [
                'success' => 'La especie y sus árboles asociados fueron eliminados correctamente'
            ];
        } else {
            return [
                'error' => 'No se pudo eliminar la especie'
            ];
        }
    } catch (PDOException $e) {
        // Log del error para el administrador
        error_log("Error al eliminar especie: " . $e->getMessage());
        
        return [
            'error' => 'Ocurrió un error al procesar la eliminación'
        ];
    }
}

public function hasTreesAssociated($speciesId): bool 
{
    try {
        $query = "SELECT COUNT(*) FROM `trees` WHERE `species_id` = :species_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':species_id' => $speciesId]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        // Log del error para el administrador
        error_log("Error al verificar árboles asociados: " . $e->getMessage());
        return false;
    }
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
