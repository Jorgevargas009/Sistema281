<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocione extends Model
{
    use HasFactory;

    protected $fillable = ['producto_id', 'descripcion', 'fecha_inicio', 'fecha_fin', 'descuento'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
