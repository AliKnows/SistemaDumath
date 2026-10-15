<?php

namespace App\Http\Controllers;

use App\Models\JugadorVisitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JugadorVisitanteController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'edad' => ['required', 'integer', 'min:1', 'max:120'],
            'rol' => ['required', 'string', 'in:estudiante,docente,colaborador'],
            'contacto' => ['required', 'string', 'max:30'],
            'opinion' => ['required', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Revisa los datos enviados.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $opinion = trim($request->string('opinion')->toString());
        $wordCount = preg_split('/\s+/', $opinion, -1, PREG_SPLIT_NO_EMPTY);

        if (count($wordCount) > 100) {
            return response()->json([
                'message' => 'La opinión no puede superar 100 palabras.',
            ], 422);
        }

        $player = JugadorVisitante::create([
            'nombre' => $request->string('nombre')->toString(),
            'edad' => $request->integer('edad'),
            'rol' => $request->string('rol')->toString(),
            'contacto' => $request->string('contacto')->toString(),
            'opinion' => $opinion,
        ]);

        return response()->json([
            'message' => 'Jugador visitante registrado.',
            'data' => $player,
        ], 201);
    }
}
