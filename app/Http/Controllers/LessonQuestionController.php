<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LessonQuestion;
use Illuminate\Http\Request;

class LessonQuestionController extends Controller
{
        public function index($lessonId)
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

        return response()->json([
            'message' => 'Pregunta creada correctamente',
            'data' => $question
        ], 201);
    }
}
