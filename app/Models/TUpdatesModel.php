<?php
namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TUpdatesModel extends Model
{
    protected $table = 'tree_updates';

    protected $fillable = [
        'tree_id',
        'update_date',
        'height',
        'image_url',
    ];

    public $timestamps = false;

    /**
     * Actualizar los datos del árbol en las tablas 'tree_updates' y 'trees'.
     *
     * @param int $tree_id
     * @param float $height
     * @param string $photo_url
     * @return bool
     */
    

    /**
     * Obtener todas las actualizaciones de árboles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllUpdates()
    {
        return self::all();
    }
    public static function getTUpdatesByTreeId($treeId)
    {
        return self::where('tree_id', $treeId)->first();
    }

}
