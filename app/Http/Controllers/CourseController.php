<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LeccionUsuario;
use App\Models\UserCourse;
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

    if (auth()->check() && $request->has('userCourses')) {

        $user = auth()->user();

        // Obtener cursos SIN paginar
        $data = $user->courses()->get();

        // Lecciones vistas del usuario (solo una query)
        $leccionesVistas = LeccionUsuario::where('user_id', $user->id)
            ->pluck('leccion_id')
            ->toArray();

        // Recorrer cursos (get() devuelve Collection, NO paginator)
        foreach ($data as $curso) {

            $contenido = json_decode($curso->contenido, true);

            if (!is_array($contenido)) {
                $curso->progreso = 0;
                continue;
            }

            $leccionesCurso = [];

            // Extraer TODAS las lecciones del JSON
            foreach ($contenido as $modulo) {
                foreach ($modulo['lessons'] as $lesson) {
                    $leccionesCurso[] = $lesson['id'];
                }
            }

            // Cantidad de lecciones vistas
            $vistas = array_intersect($leccionesCurso, $leccionesVistas);

            // Calcular progreso
            $total = count($leccionesCurso);
            $curso->progreso = $total > 0
                ? round((count($vistas) / $total) * 100)
                : 0;

            // Opcional: lista de lecciones vistas
            $curso->lecciones_vistas = $vistas;
        }

    } else {

        // Si NO es userCourses -> paginar cursos normales
        $query = Course::query();

        if ($request->has('query')) {

            if ($request->filled('titulo')) {
                $query->where('titulo', 'like', '%' . trim($request->titulo) . '%');
            }

            if ($request->filled('categoria')) {
                $query->orWhere('categoria', 'like', '%' . trim($request->categoria) . '%');
            }

            if ($request->filled('nivel')) {
                $query->orWhere('nivel', 'like', '%' . trim($request->nivel) . '%');
            }
        }

        // paginator aquí SÍ tiene items()
        $data = $query->paginate(9);
    }

    return response()->json($data, 200)
        ->header('X-Powered-By', 'AcademiaCristal API');

} catch (\Throwable $e) {

    return response()->json([
        'message' => 'Operación fallida',
        'error' => $e->getMessage()
    ], 500);
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

             if(auth()->check()){
                $user = auth()->user();
                $modelo->buyed = UserCourse::where('user_id', $user->id)
                ->where('course_id', $id)
                ->exists();
            }

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
