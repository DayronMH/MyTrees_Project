<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Importar la clase Log
use App\Models\SpeciesModel;

class SpeciesController
{
    public function getAllSpecies()
    {
        try {
            // Registrar en el log antes de obtener las especies
            Log::info('Obteniendo todas las especies desde getAllSpecies.');

            $species = SpeciesModel::all();

            // Registrar en el log el número de especies recuperadas
            Log::info('Especies obtenidas:', ['count' => count($species)]);

            return response()->json($species);
        } catch (\Exception $e) {
            // Registrar errores en el log
            Log::error('Error al obtener todas las especies.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }

    public function getSpeciesById($id)
    {
        try {
            // Registrar en el log antes de buscar una especie
            Log::info("Buscando especie con ID: $id desde getSpeciesById.");

            $species = SpeciesModel::getSpeciesById($id);

            if ($species) {
                // Registrar en el log si la especie fue encontrada
                Log::info('Especie encontrada:', ['id' => $id, 'species' => $species]);

                return response()->json($species, 200);
            } else {
                // Registrar en el log si no se encontró la especie
                Log::warning("Especie no encontrada con ID: $id.");

                return response()->json(['message' => 'Especie no encontrada'], 404);
            }
        } catch (\Exception $e) {
            // Registrar errores en el log
            Log::error('Error al obtener la especie por ID.', [
                'error' => $e->getMessage(),
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Error interno del servidor'], 500);
        }
    }
    public function createSpecie(Request $request)
    {
        // Validación de los datos (sin incluir availability_date, porque lo asignaremos automáticamente)
        $request->validate([
            'commercial_name' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
        ]);

        try {
            // Crear nueva especie, asignando la fecha y hora actual a availability_date
            $species = SpeciesModel::create([
                'commercial_name' => $request->commercial_name,
                'scientific_name' => $request->scientific_name,
                'availability_date' => now(), // Asigna la fecha y hora actual
            ]);

            Log::info('Especie creada exitosamente: ' . $species->commercial_name);
            return response()->json($species, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear la especie: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear la especie'], 500);
        }
    }
    public function updateSpecie(Request $request, $id)
    {
        $species = SpeciesModel::find($id);

        if (!$species) {
            return response()->json(['message' => 'Especie no encontrada'], 404);
        }
        $species->commercial_name = $request->input('commercial_name', $species->commercial_name);  // Mantener valor anterior si no se envía uno nuevo
        $species->scientific_name = $request->input('scientific_name', $species->scientific_name);  // Mantener valor anterior si no se envía uno nuevo
        $species->save();

        return response()->json(['message' => 'Especie actualizada con éxito', 'species' => $species], 200);
    }

    public function deleteSpecie($id)
    {
        try {

            $species = SpeciesModel::find($id);

            if ($species) {
                $species->delete();
                return response()->json(['message' => 'Especie eliminada con éxito'], 200);
            } else {
                return response()->json(['message' => 'Especie no encontrada'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar la especie', 'error' => $e->getMessage()], 500);
        }
    }
}
