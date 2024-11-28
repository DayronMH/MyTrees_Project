<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use speciesModel;

class TreesModel extends Model
{
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

    // Relación con Species
    public function species()
    {
        return $this->belongsTo(speciesModel::class, 'species_id');
    }

    // Relación con User (owner)
    public function owner()
    {
        return $this->belongsTo(UsersModel::class, 'owner_id');
    }

    // Método para crear un árbol básico
    public static function createTreeBasic(int $species_id, string $location, float $price, string $photo_url)
    {
            return self::create([
            'species_id' => $species_id,
            'location' => $location,
            'price' => $price,
            'photo_url' => $photo_url
        ]);
    }

    // Método para editar árbol
    public function updateTreeDetails($specie, $height, $location, $available)
    {
        return DB::transaction(function () use ($specie, $height, $location, $available) {
            // Actualizar el árbol
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

    // Contar árboles disponibles
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

    // Obtener árboles por propietario
    public static function getTreesByOwner(int $owner_id)
    {
        return self::with(['species', 'owner'])
            ->where('owner_id', $owner_id)
            ->get();
    }
}