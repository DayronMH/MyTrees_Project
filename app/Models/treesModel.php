<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TreesModel extends Model
{
    public $timestamps = false;
    protected $table = 'trees';
    protected $fillable = [
        'species_id', 
        'location', 
        'price', 
        'photo_url', 
        'height', 
        'available',
        'owner_id'
    ];
    public static function countSoldTrees()
    {
        return self::where('available', false)->count();
    }

    public static function createTreeBasic(int $species_id, string $location, float $price, string $photo_url)
    {
            return self::create([
            'species_id' => $species_id,
            'location' => $location,
            'price' => $price,
            'photo_url' => $photo_url
        ]);
    }

    public static function getTreeById($id)
    {
        return self::find($id);
    }
    public function updateTreeDetails($specie, $height, $location, $available)
    {
        return DB::transaction(function () use ($specie, $height, $location, $available) {
            $this->update([
                'height' => $height,
                'location' => $location,
                'available' => $available
            ]);

            // Actualizar especie si es necesario
            $this->species->update([
                'commercial_name' => $specie
            ]);

            return true;
        });
    }

    public function buyTree($treeId, $ownerId)
    {
        return DB::transaction(function () use ($treeId, $ownerId) {
            // Obtener el árbol por su ID
            $tree = $this->find($treeId);
    
            // Validar si el árbol existe y está disponible
            if (!$tree) {
                return ['success' => false, 'message' => 'El árbol no existe.'];
            }
    
            if ($tree->available == 0) {
                return ['success' => false, 'message' => 'El árbol no está disponible para la compra.'];
            }
    
            // Actualizar el árbol: asignar el propietario y marcar como no disponible
            $tree->update([
                'owner_id' => $ownerId,
                'available' => 0, // Marcado como no disponible
            ]);
    
            return ['success' => true, 'message' => 'Compra realizada con éxito.'];
        });
    }
    

    public static function countAvailableTrees()
    {
        return self::where('available', true)->count();
    }

    // Obtener árboles disponibles con especies
    public static function getAvailableTreesWithSpecies()
    {
        return DB::table('trees')
        ->join('species', 'trees.species_id', '=', 'species.id')
        ->where('trees.available', true)
        ->select(
            'trees.id as tree_id',
            'trees.height',
            'trees.location',
            'trees.photo_url',
            'trees.price',
            'species.commercial_name',
        )
        ->get();
    }
  
public static function getSoldTreesWithOwnerAndSpecies()
{
    return DB::table('trees')
        ->join('species', 'trees.species_id', '=', 'species.id')
        ->join('users', 'trees.owner_id', '=', 'users.id')
        ->where('trees.available', false)
        ->select(
            'trees.id as tree_id',
            'species.commercial_name',
            'users.name as owner_name',
            'users.id as owner_id'
        )
        ->get();
}

    public static function deleteTree($id)
    {
        $tree = self::find($id);

        if ($tree) {
            return $tree->delete();
        }

        return false;
    }
    public static function getTreesByOwner($ownerId)
    {
        return self::where('owner_id', $ownerId)->get();
    }
  
}