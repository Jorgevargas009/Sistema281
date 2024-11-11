<?php

namespace App\Http\Controllers;

use App\Models\Reseña;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    //
    public function guardar(Request $request)
    {
        // Validar la solicitud para múltiples productos
        $request->validate([
            'producto_id' => 'required|array',
            'producto_id.*' => 'required|exists:productos,id',
            'calificacion' => 'required|array',
            'calificacion.*' => 'required|integer|between:1,5',
            'comentario' => 'nullable|array',
            'comentario.*' => 'nullable|string',
        ]);

        // Iterar sobre los productos para guardar cada reseña
        foreach ($request->producto_id as $index => $productoId) {
            $calificacion = $request->calificacion[$index];
            $comentario = $request->comentario[$index] ?? null;

            Reseña::create([
                'user_id' => auth()->id(), // ID del usuario autenticado
                'producto_id' => $productoId,
                'calificacion' => $calificacion,
                'comentario' => $comentario,
                'fecha_reseña' => now(),
            ]);
        }

        return response()->json(['message' => 'Reseñas guardadas con éxito.']);
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
