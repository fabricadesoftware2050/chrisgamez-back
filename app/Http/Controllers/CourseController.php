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

            // Obtener cursos del usuario
            $data = $user->courses()->get();

            // Lecciones vistas del usuario (más adelante obtendremos el detalle)
            $leccionesVistas = LeccionUsuario::where('user_id', $user->id)
                ->pluck('leccion_id')
                ->toArray();

            // También obtenemos el DETALLE completo para después filtrarlo por curso
            $leccionesVistasDetalleGlobal = LeccionUsuario::where('user_id', $user->id)
                ->get(['course_id', 'lesson_id', 'viewed_at']);

            // ACUMULADORES GLOBALES
            $totalMinutosVistos = 0;
            $totalMinutosDisponibles = 0;

            foreach ($data as $curso) {

                // Asegurar que contenido sea array
                $contenido = is_string($curso->contenido)
                    ? json_decode($curso->contenido, true)
                    : $curso->contenido;

                if (!is_array($contenido)) {
                    $curso->progreso = 0;
                    $curso->horas_vistas = 0;
                    continue;
                }

                $leccionesCurso = [];
                $cursoMinutosTotales = 0;
                $cursoMinutosVistos = 0;

                // Recorrer módulos y lecciones
                foreach ($contenido as $modulo) {

                    if (!isset($modulo['lessons'])) continue;

                    foreach ($modulo['lessons'] as $lesson) {

                        $leccionId = $lesson['id'] ?? null;
                        $duration = $lesson['duration'] ?? "0m";

                        // Convertir duración a minutos
                        $minutos = 0;

                        if (preg_match('/(\d+)m/', $duration, $m)) {
                            $minutos += intval($m[1]);
                        }

                        if (preg_match('/(\d+)h/', $duration, $h)) {
                            $minutos += intval($h[1]) * 60;
                        }

                        // Sumar al tiempo total
                        $cursoMinutosTotales += $minutos;

                        // Si el usuario vio esta lección → sumar tiempo
                        if (in_array($leccionId, $leccionesVistas)) {
                            $cursoMinutosVistos += $minutos;
                        }

                        $leccionesCurso[] = $leccionId;
                    }
                }

                // Guardar acumulados globales
                $totalMinutosDisponibles += $cursoMinutosTotales;
                $totalMinutosVistos += $cursoMinutosVistos;

                // Progreso por curso
                $curso->progreso = $cursoMinutosTotales > 0
                    ? round(($cursoMinutosVistos / $cursoMinutosTotales) * 100)
                    : 0;

                // Campos adicionales
                $curso->minutos_totales = $cursoMinutosTotales;
                $curso->minutos_vistos = $cursoMinutosVistos;
                $curso->horas_vistas = round($cursoMinutosVistos / 60, 2);

                // IDs de lecciones vistas
                $curso->lecciones_vistas = array_values(
                    array_intersect($leccionesCurso, $leccionesVistas)
                );

                // DETALLE filtrado SOLO de las lecciones de este curso
                $curso->lecciones_vistas_detalle = $leccionesVistasDetalleGlobal
                    ->where('course_id', $curso->id)
                    ->whereIn('lesson_id', $curso->lecciones_vistas)
                    ->values();
            }

            // AGREGAR DATOS GLOBALES
            $data->total_horas_vistas = round($totalMinutosVistos / 60, 2);
            $data->total_horas_totales = round($totalMinutosDisponibles / 60, 2);
            $data->total_progreso_global = $totalMinutosDisponibles > 0
                ? round(($totalMinutosVistos / $totalMinutosDisponibles) * 100)
                : 0;

        } else {

            // Listado público paginado
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

            $data = $query->paginate(9);
        }

        return response()->json([
            'message' => 'Operación exitosa',
            'data' => $data
        ], 200)
            ->header('X-Powered-By', 'AcademiaCristal API');


    } catch (\Throwable $e) {

        return response()->json([
            'message' => 'Operación fallida',
            'error' => $e->getMessage(),
            'line' => $e->getLine()
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
