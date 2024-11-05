<?php
require_once 'baseModel.php';

class speciesModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct('species');
    }

    public function createSpecie(string $commercial_name, string $scientific_name): bool
    {
        date_default_timezone_set('America/Costa_Rica');
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

    public function deleteSpecie(int $id): array
{
    try {
        if ($this->hasTreesAssociated($id)) {
            $deleteRelatedTreesQuery = "DELETE FROM `trees` WHERE `species_id` = :species_id";
            $relatedTreesDeleted = $this->executeQuery($deleteRelatedTreesQuery, [':species_id' => $id]);
            
            if (!$relatedTreesDeleted) {
                return [
                    'error' => 'No se pudieron eliminar los árboles asociados a esta especie'
                ];
            }
        }
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
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }

    private function executeQuery(string $query, array $params = null)
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params ?? []);
            return $params ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
