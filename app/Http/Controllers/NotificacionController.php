<?php

namespace App\Http\Controllers;

use App\Models\Notificacione;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function marcarComoLeida($id)
    {
        $notificacion = Notificacione::find($id);
        
        if ($notificacion) {
            $notificacion->leida = true; // Cambiar el estado a leído
            $notificacion->save();
        }

        return redirect()->back(); // Redirigir de nuevo a la página anterior
    }
}
