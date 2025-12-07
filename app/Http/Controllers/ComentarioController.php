<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;

use Exception;

class ComentarioController extends Controller
{
    public function index()
    {
        return response()->json(Comentario::with('videos')->orderBy('created_at','desc')->orderBy('id','desc')->get());
    }

    public function create()
    {
        // No se usa normalmente en APIs, solo en vistas.
    }

    public function store(Request $request)
    {
        try{
        $validated = $request->validate([
            'user' => 'nullable',
            'message' => 'nullable',
            'lesson_id' => 'required'
        ]);
        $validated['ip'] = $request->ip();

        $model = Comentario::create($validated);
        return response()->json($model, 201);
        }catch(Exception $ex){
            return response()->json($ex, 500);
        }
    }

    public function show(string $id,Request $request)
    {

        $model = Comentario::where('lesson_id',$id)->orderBy('created_at','desc')->limit($request->input('limit'))->get();
        if($model){
            return response()->json($model);
        }else return response()->json('no se encontraron comentarios',404);



    }

    public function edit(string $id)
    {
        // No se usa normalmente en APIs, solo en vistas.
    }

    public function update(Request $request, string $id)
    {
        $c = Comentario::findOrFail($id);

        $validated = $request->validate([
            'ip' => 'nullable|string',
            'user' => 'nullable',
            'message' => 'nullable',
            'lesson_id' => 'required'
        ]);

        $c->update($validated);
        return response()->json($c);
    }

    public function destroy(string $id)
    {
        $c = Comentario::findOrFail($id);
        $c->delete();

        return response()->json(['message' => 'Eliminado correctamente.']);
    }
}
