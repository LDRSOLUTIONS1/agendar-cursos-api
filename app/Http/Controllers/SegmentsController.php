<?php

namespace App\Http\Controllers;

use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SegmentsController extends Controller
{
    public function index()
    {
        try {
            $segments = Segment::all();
            return response()->json($segments, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los segmentos', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $segment = Segment::findOrFail($id);
            return response()->json($segment, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Segmento no encontrado', 'mensaje' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
                'estado' => 'nullable|integer|in:0,1,2',
            ], [
                'name.required' => 'El nombre es obligatorio',
                'name.max' => 'El nombre no puede tener más de 255 caracteres',
                'name.unique' => 'El nombre ya existe',
                'estado.integer' => 'El estado debe ser un número entero',
                'estado.in' => 'El estado debe ser 0 (Eliminado), 1 (Inactivo) o 2 (Activo)',
            ]);

            $segment = Segment::create($validated);

            return response()->json(['mensaje' => 'Segmento creado correctamente', 'segmento' => $segment], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el segmento', 'mensaje' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $segment = Segment::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:segments,name,' . $segment->id,
                'description' => 'nullable|string',
                'estado' => 'nullable|integer|in:0,1,2',
            ], [
                'name.required' => 'El nombre es obligatorio',
                'name.max' => 'El nombre no puede tener más de 255 caracteres',
                'name.unique' => 'El nombre ya existe',
                'estado.integer' => 'El estado debe ser un número entero',
                'estado.in' => 'El estado debe ser 0 (Eliminado), 1 (Inactivo) o 2 (Activo)',
            ]);

            $segment->update($validated);

            return response()->json(['mensaje' => 'Segmento actualizado correctamente', 'segmento' => $segment], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el segmento', 'mensaje' => $e->getMessage()], 500);
        }
    }
}
