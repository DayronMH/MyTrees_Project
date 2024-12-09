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

    public static function countAvailableTrees()
    {
        return self::where('available', true)->count();
    }

    // Obtener árboles disponibles con especies
    public static function getAvailableTreesWithSpecies()
    {
        return self::with('species')
            ->where('available', true)
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