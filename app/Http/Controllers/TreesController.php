<?php

namespace app\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use app\Models\TreesModel;
use app\Models\TUpdatesModel;
use Illuminate\Support\Facades\Auth;

class TreesController
{
    public function updateTrees(Request $request, $tree_id)
    {
        try {
            $validated = $request->validate([
                'height' => 'required|numeric|min:0|max:1000',
                'photo' => 'nullable|image|max:2048', // Validación para la foto
            ]);

            $tree = TreesModel::find($tree_id);

            if (!$tree) {
                return response()->json([
                    'error' => true,
                    'message' => 'Árbol no encontrado',
                    'code' => 'TREE_NOT_FOUND'
                ], 404);
            }
            $tree->height = $validated['height'];

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoPath = $photo->store('trees', 'public'); // Guardar la foto en la carpeta 'trees' en almacenamiento público
                $tree->photo_url = asset('storage/' . $photoPath); // Crear la URL accesible públicamente
            }
            $tree->save();

            TUpdatesModel::create([
                'tree_id' => $tree_id,
                'image_url' => $tree->photo_url, 
                'height' => $validated['height'], 
                'update_date' => now(),
            ]);

            // Respuesta exitosa
            return response()->json([
                'error' => false,
                'message' => 'Árbol actualizado exitosamente',
                'data' => $tree
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
        
            return response()->json([
                'error' => true,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            
            Log::error('Error al actualizar el árbol', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Error interno del servidor',
                'details' => $e->getMessage(),
                'code' => 'INTERNAL_ERROR'
            ], 500);
        }
    }public function createTree(Request $request)
    {
        $request->validate([
            'species_id' => 'required|integer',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Validación de imagen
        ]);
    
        try {
            $photoPath = $request->file('photo')->store('trees', 'public');  // Guardar la imagen en la carpeta 'trees' en almacenamiento público
            $photoUrl = asset('storage/' . $photoPath);  // Crear la URL accesible públicamente
    
            // Crear el árbol en la base de datos
            $tree = TreesModel::createTreeBasic(
                $request->species_id,
                $request->location,
                $request->price,
                $photoUrl  
            );
    
            if ($tree) {
                Log::info('Árbol creado exitosamente', ['tree' => $tree]);
                return response()->json(['message' => 'Árbol creado exitosamente'], 200);
            } else {
                Log::warning('No se pudo crear el árbol');
                return response()->json(['message' => 'No se pudo crear el árbol'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error al crear el árbol', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error al crear el árbol'], 500);
        }
    }
        public function getTreesById($id)
    {
        try {
            // Registrar en el log antes de buscar una especie
            Log::info("Buscando arbol con ID: $id ");

            $trees = TreesModel::getTreeById($id);

            if ($trees) {
                // Registrar en el log si la especie fue encontrada
                Log::info('Arbol encontrado', ['id' => $id, 'arbol' => $trees]);

                return response()->json($trees, 200);
            } else {
                // Registrar en el log si no se encontró la especie
                Log::warning("Arbol no encontrado con ID: $id.");

                return response()->json(['message' => 'Arbol no encontrada'], 404);
            }
        } catch (\Exception $e) {
            // Registrar errores en el log
            Log::error('Error al obtener El arbol por ID.', [
                'error' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }
    public function getSoldTrees()
    {
        $soldTreesCount = TreesModel::countSoldTrees();



        return response()->json([
            'soldTrees' => $soldTreesCount
        ]);
    }
    public function getAvailableTrees()
    {
        $availableTreesCount = TreesModel::countAvailableTrees();
        return response()->json([
            'availableTrees' => $availableTreesCount
        ]);
    }
    public function getAllAvailableTrees()
    {
        // Obtener los árboles disponibles con sus especies
        $availableTrees = TreesModel::getAvailableTreesWithSpecies();

        return response()->json([
            'availableTrees' => $availableTrees
        ]);
    }
    public function getTreesByOwner($ownerId)
    {
        try {
            // Llamamos a la función del modelo para obtener los árboles por owner_id
            $trees = TreesModel::getTreesByOwner($ownerId);

            // Si no hay árboles, devolvemos un mensaje adecuado
            if ($trees->isEmpty()) {
                return response()->json(['message' => 'No se encontraron árboles para este dueño.'], 404);
            }

            // Si hay árboles, los retornamos
            return response()->json($trees, 200);
        } catch (\Exception $e) {
            // En caso de error, devolvemos un mensaje de error
            return response()->json(['message' => 'Error al obtener los árboles.', 'error' => $e->getMessage()], 500);
        }
    }
    public function deleteTree($id)
    {
        try {

            $tree = TreesModel::find($id);

            if ($tree) {
                $tree->delete();
                return response()->json(['message' => 'Arbol eliminado con éxito'], 200);
            } else {
                return response()->json(['message' => 'Arbol no encontrado'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el arbol', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateTree(Request $request, $id)
    {
        try {
            $tree = TreesModel::find($id);
            if (!$tree) {
                return response()->json(['message' => 'Árbol no encontrado.'], 404);
            }

            // Validar los datos recibidos
            $request->validate([
                'height' => 'required|numeric|min:0',
                'species_id' => 'required|exists:species,id',  // Asegura que la especie exista
                'location' => 'required|string|max:255',
                'available' => 'required|boolean',  // Acepta valores 0 o 1
            ]);

            // Actualizar los valores en el modelo
            $tree->height = $request->input('height', $tree->height);  // Si no se envía, mantiene el valor actual
            $tree->species_id = $request->input('species_id', $tree->species_id);  // Actualizar ID de especie
            $tree->location = $request->input('location', $tree->location);  // Actualizar ubicación
            $tree->available = $request->input('available', $tree->available);  // Actualizar disponibilidad

            // Guardar los cambios
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
}
