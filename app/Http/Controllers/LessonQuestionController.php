<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class LessonQuestionController extends Controller
{
    public function show($lessonId)
    {
        $questions = LessonQuestion::where('lesson_id', $lessonId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $questions
        ]);
    }


    public function store(Request $request, $lessonId)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $question = LessonQuestion::create([
            'lesson_id' => $lessonId,
            'title'     => $request->title,
            'content'   => $request->content,
        ]);
        $modelo = Lesson::find($lessonId);
        Mail::raw(
            "Nuevo comentario en la lección:\n\n" .
            "Lección: {$modelo->title}\n" .
            "Usuario: " . auth()->user()->name . "\n\n" .
            "Comentario:\n" .
            $request->content . "\n\n" .
            "Fecha: " . date('d/m/Y H:i'),
            function ($msg) {
                $msg->to('info@chrisgamez.com')
                    ->subject('Nuevo comentario en una lección');
            }
        );

        return response()->json([
            'message' => 'Pregunta creada correctamente',
            'data' => $question
        ], 201);
    }
}
