<?php

namespace App\Http\Controllers;

use App\Models\Reseña;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    //
    public function guardar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'calificacion' => 'required|integer|between:1,5',
            'comentario' => 'nullable|string',
        ]);

        Reseña::create([
            'user_id' => auth()->id(), // Asignar el ID del usuario autenticado
            'producto_id' => $request->producto_id,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'fecha_reseña' => now(),
        ]);

        return response()->json(['message' => 'Reseña guardada con éxito.']);
    }
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'texto' => 'required|string|max:255',
        ]);

        // Crear una nueva reseña
        $resena = new Reseña();
        $resena->producto_id = $request->producto_id;
        $resena->texto = $request->texto;
        $resena->user_id = auth()->id(); // Asegúrate de que el usuario esté autenticado
        $resena->save();

        // Respuesta de éxito
        return response()->json(['message' => 'Reseña guardada con éxito.']);
    }
}
