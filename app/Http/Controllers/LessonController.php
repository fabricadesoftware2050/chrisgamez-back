<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LeccionUsuario;
use App\Models\Lesson;
use App\Models\UserCourse;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        try {
            $modelo = Lesson::find($id);

            if($modelo && str_contains($modelo->url_video,'cursos' )){
                $disk = Storage::disk('spaces');

                $url = $disk->temporaryUrl(
                    $modelo->url_video,   // ruta dentro del Space
                    now()->addMinutes(30),    // tiempo de expiración
                    [
                        'ResponseContentType' => 'video/mp4',
                        'ResponseContentDisposition' => 'inline',  // 👈 evita que se descargue
                    ]
                );



                $modelo->url_video = $url;
                //$modelo->url_video = route('video.stream', $id);
;
            }
            $curso = Course::find($request->courseId);
            if (!$modelo || !$curso) {
                return response()->json([
                    'error'   => 'Resource not found',
                    'message' => 'No se encontró el recurso solicitado'
                ], 404);
            }else {


                if($modelo->isFree || auth()->check()){
                    $modelo->course = $curso;
                    $modelo->course['buyed'] = false;
                    if(auth()->check()){
                        $user = auth()->user();
                        $modelo->course['buyed'] = UserCourse::where('user_id', $user->id)
                        ->where('course_id', $request->courseId)
                        ->exists();
                        if($modelo->course['buyed']){
                            LeccionUsuario::firstOrCreate([
                                'user_id' => $user->id,
                                'lesson_id' => $modelo->id,
                            ]);
                        }
                    }
                    return response()->json([
                        'message' => 'Operación exitosa',
                        'data' => $modelo
                    ], 200);
                }else{
                        return response()->json([
                            'error'   => 'Unauthorized',
                            'message' => 'Debe iniciar sesión para acceder a este recurso'
                        ], 401);
                    }
            }
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function stream($id)
{
     $modelo = Lesson::find($id);
    $disk = Storage::disk('spaces');

    if (!$disk->exists($modelo->url_video)) {
        abort(404);
    }

    $stream = $disk->readStream($modelo->url_video);

    return response()->stream(function () use ($stream) {
        fpassthru($stream);
    }, 200, [
        'Content-Type' => 'video/mp4',
        'Content-Disposition' => 'inline',
        'Accept-Ranges' => 'bytes',
        'Cache-Control' => 'no-store',
    ]);
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
        //
    }
}
