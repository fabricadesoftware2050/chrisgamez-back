<?php

// app/Http/Controllers/PagoController.php
namespace App\Http\Controllers;

use App\Models\UserCourse;
use App\Models\Pago;
use Exception;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function store(Request $request)
    {

        if(auth()->check()){
        $user = auth()->user();

            // Validar entrada
            $validated = $request->validate([
                'user_id' => 'nullable',
                'course_id' => 'nullable',
                'price' => 'nullable',
                //'detalle' => 'nullable|array',
            ]);

            // Convertir datos entrantes a objeto
            $evento = json_decode(json_encode($request->all()));
        try {




            // Asignar datos validados
            $validated['detalle'] = json_encode($evento);
            $validated['user_id'] = $user->id;
            $validated['course_id'] = intval($evento->course_id);



            $validated['price'] = intval($evento->price) ;
            $validated['status'] = $evento->status;
            $validated['id_transaction'] = $evento->id_transaction;

            // Crear registro de pago
            $existentePago = Pago::where('user_id', $validated['user_id'])->where('course_id', $validated['course_id'])->first();
            if($existentePago){
                return response()->json([
                    'message' => 'El pago ya ha sido registrado previamente',
                    'pago' => $existentePago
                ], 200);
            }
            $pago = Pago::create($validated);

        if($validated['status']=="APPROVED" && !$existentePago){
            // Asociar usuario con curso
            UserCourse::create([
                'user_id' => $validated['user_id'],
                'course_id' => $validated['course_id']
            ]);
        }

        } catch (\Throwable $e) {


            // Puedes registrar el error o devolver respuesta de error
            \Log::error('Error al procesar evento de pago: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago',
                'error' => $e->getMessage()
            ], 500);
        }



        return response()->json([
            'message' => 'Pago registrado con éxito',
            'pago' => $pago
        ], 201);
    }else{
         // Validar entrada

            // Convertir datos entrantes a objeto
            $evento = $request->all();
        try {
            // Asignar datos validados
            $validated['detalle'] = json_encode($evento);
            $validated['user_id'] = intval(explode("-", $evento['data']['transaction']['reference'])[1]);
            $validated['course_id'] = intval(explode("-", $evento['data']['transaction']['reference'])[0]);



            $validated['price'] = intval($evento['data']['transaction']['amount_in_cents'])/100 ;
            $validated['status'] = $evento['data']['transaction']['status'];
            $validated['id_transaction'] = $evento['data']['transaction']['id'];

            // Crear registro de pago
            $pago = Pago::create($validated);

        if($validated['status']=="APPROVED"){
            // Asociar usuario con curso
            UserCourse::create([
                'user_id' => $validated['user_id'],
                'course_id' => $validated['course_id']
            ]);
        }

        } catch (\Throwable $e) {


            // Puedes registrar el error o devolver respuesta de error
            \Log::error('Error al procesar evento de pago: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago',
                'error' => $e->getMessage()
            ], 500);
        }



        return response()->json([
            'message' => 'Pago registrado con éxito',
            'pago' => $pago
        ], 201);

    }
}

    public function index()
    {
        return Pago::with(['usuario', 'curso'])->latest()->get();
    }

    public function show($id)
    {
       $pago = Pago::with(['usuario.courses'])->where('id_transaction', $id)->first();

        if ($pago && $pago->usuario) {
            $cursoIds = $pago->usuario->courses->pluck('id')->toArray();
            // Sobrescribimos la relación con solo los IDs
            $pago->usuario->setRelation('courses', collect($cursoIds));
        } else {
            $pago['usuario']['courses'] = [];
        }

        return response()->json($pago);

    }
}
