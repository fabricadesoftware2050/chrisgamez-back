<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mews\Purifier\Facades\Purifier;


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
            'content'   => Purifier::clean($request->content),
        ]);
        $modelo = Lesson::find($lessonId);
        $lessonUrl = $request->lesson_url;
        Mail::html(
            "
    <h2>📢 Nuevo comentario en el curso {$request->title}</h2>

    <p><strong>Lección:</strong> {$modelo->title}</p>
    <p style='margin-top:20px'>
        <a href='{$lessonUrl}'
           target='_blank'
           rel='noopener noreferrer'
           style='
                display:inline-block;
                padding:10px 16px;
                background:#2563eb;
                color:#ffffff;
                text-decoration:none;
                border-radius:6px;
                font-weight:bold;
           '>
            Ver lección
        </a>
    </p>

    <p>
        <strong>Estudiante:</strong> " . auth()->user()->name . "<br>
        <strong>Email:</strong> " . auth()->user()->email . "
    </p>

    <hr>

    <p><strong>Comentario:</strong></p>

    <div style='background:#f9fafb; padding:12px; border-left:4px solid #2563eb'>
        {$request->content}
    </div>

    <p style='margin-top:20px; font-size:12px; color:#6b7280'>
        Fecha: " . date('d/m/Y H:i') . "
    </p>
    ",
            function ($msg) {
                $msg->to('info@chrisgamez.com')
                    ->cc(auth()->user()->email)
                    ->from('info@chrisgamez.com', 'CURSOS CHRIS GAMEZ')
                    ->subject('Nuevo comentario');
            }
        );

        return response()->json([
            'message' => 'Pregunta creada correctamente',
            'data' => $question
        ], 201);
    }
}
