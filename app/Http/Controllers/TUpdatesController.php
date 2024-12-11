<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TUpdatesModel;
use Illuminate\Support\Facades\Log;

class TUpdatesController
{
    public function updateTree(Request $request, $id)
    {
        try {
            // Encuentra el árbol por su ID en el modelo correspondiente
            $tree = TUpdatesModel::find($id); // Reemplaza 'Tree' con el nombre de tu modelo
            if (!$tree) {
                return response()->json(['message' => 'Árbol no encontrado.'], 404);
            }

            // Validación de los datos recibidos
            $request->validate([
                'height' => 'required|numeric|min:0', // Altura no negativa
                'photo_url' => 'required|url', // URL válida
            ]);

            // Actualiza los campos de la base de datos con los datos recibidos
            $tree->height = $request->input('height', $tree->height);
            $tree->photo_url = $request->input('photo_url', $tree->photo_url);

            // Guarda los cambios en la base de datos
            $tree->save();

            // Respuesta exitosa
            return response()->json([
                'message' => 'Árbol actualizado con éxito.',
                'tree' => $tree
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'message' => 'Error al actualizar el árbol.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function getAllUpdates()
    {
        try {
            // Obtener todos los registros de la tabla
            $updates = TUpdatesModel::getAllUpdates();

            // Respuesta exitosa con los datos
            return response()->json([
                'message' => 'Datos obtenidos con éxito.',
                'updates' => $updates,
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            Log::error('Error al obtener los datos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener los datos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getTreeUpdateById($treeId)
    {
        try {
            // Log para ver qué ID estamos buscando
            Log::debug("Buscando árbol con ID: $treeId ");

            // Obtener los registros de actualizaciones del árbol
            $trees = TUpdatesModel::getTUpdatesByTreeId($treeId);

            if ($trees ) {
                // Si el árbol existe y tiene actualizaciones, registramos el éxito
                Log::info('Árbol encontrado', ['id' => $treeId, 'actualizaciones' => $trees]);

                return response()->json($trees, 200); // Retorna las actualizaciones del árbol
            } else {
                // Si no encontramos el árbol o no tiene actualizaciones
                Log::warning("Árbol no encontrado o no tiene actualizaciones con ID: $treeId.");

                return response()->json(['message' => 'Árbol no encontrado o sin actualizaciones'], 404);
            }
        } catch (\Exception $e) {
            // Log de error con detalles del fallo
            Log::error('Error al obtener el árbol por ID.', [
                'error' => $e->getMessage(),
                'id' => $treeId,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error interno del servidor. Intente nuevamente más tarde.'], 500);
        }
    }
}
