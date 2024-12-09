<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeciesModel extends Model
{
    
    protected $table = 'species';
    protected $fillable = [
        'commercial_name',
        'scientific_name',
        'availability_date',
    ];
    public $timestamps = false;

    /**
     * Obtener todas las especies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllSpecies()
    {
        return self::all();
    }

    /**
     * Obtener una especie por ID.
     *
     * @param int $id
     * @return \App\Models\SpeciesModel|null
     */
    public static function getSpeciesById($id)
    {
        return self::find($id);
    }

    /**
     * Crear una nueva especie.
     *
     * @param array $data
     * @return \App\Models\SpeciesModel
     */
    public static function createSpecies($data)
    {
        return self::create($data);
    }

    /**
     * Actualizar una especie existente.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateSpecies($id, $data)
    {
        $species = self::find($id);

        if ($species) {
            return $species->update($data);
        }

        return false;
    }

    /**
     * Eliminar una especie por ID.
     *
     * @param int $id
     * @return bool|null
     */
    public static function deleteSpecies($id)
    {
        $species = self::find($id);

        if ($species) {
            return $species->delete();
        }

        return false;
    }
}
