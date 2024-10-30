<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacione extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mensaje',
        'fecha_envio',
        'leida',
    ];
    protected $dates = [
        'fecha_envio', // Asegúrate de que este campo sea un objeto Carbon
    ];
    
    public static function crearNotificacion($userId, $mensaje)
    {
        return self::create([
            'user_id' => $userId,
            'mensaje' => $mensaje,
            'leida' => false,
            'fecha_envio' => now(), // Establece la fecha de envío a la fecha actual
        ]);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
