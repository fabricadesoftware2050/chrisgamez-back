<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Exception;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Course::query();

            // Apply filters if provided
            if ($request->has('query')) {
                if ($request->filled('titulo')) {
                    $query->where('titulo', 'like', '%' . trim($request->input('titulo')) . '%');
                }

                if ($request->filled('categoria')) {
                    $query->orWhere('categoria', 'like', '%' . trim($request->input('categoria')) . '%');
                }

                if ($request->filled('nivel')) {
                    $query->orWhere('nivel', 'like', '%' . trim($request->input('nivel')) . '%');
                }
            }

            // Paginate the results
            $data = $query->paginate(9);

            return response()->json($data, 200)->header('X-Powered-By', 'AcademiaCristal API');

        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Operación fallida',
                'error' => $ex->getMessage()
            ], 500)->header('X-Powered-By', 'AcademiaCristal API');
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->has('id') && !empty($request->id)) {
                // Buscar el registro existente
                $modelo = Course::findOrFail($request->id);

                // Actualizar con los nuevos datos
                $modelo->update($request->all());
                $data = Course::paginate(10);
                return response()->json([
                    'message' => 'Operación exitosa',
                    'data' => $data
                ], 200);
            } else {
                // Si no hay id → crear nuevo
                $modelo = Course::create($request->all());
                $data = Course::paginate(10);

                return response()->json([
                    'message' => 'Operación exitosa',
                    'data' => $data
                ], 201);
            }

        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $modelo = Course::findOrFail($id);

            return response()->json([
                'message' => 'Operación exitosa',
                'data' => $modelo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $modelo = Course::findOrFail($id);
            $modelo->delete();

            return response()->json(['message' => 'Registro eliminado con éxito']);
        } catch (Exception $e) {
            return response()->json(['error' => 'El registro está en uso o acción restringida', 'message' => $e->getMessage()], 500);
        }
    }
}
