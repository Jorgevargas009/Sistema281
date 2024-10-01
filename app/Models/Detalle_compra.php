<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_compra extends Model
{
    use HasFactory;

    protected $fillable = ['carro_compra_id', 'producto_id', 'cantidad'];

    public function carro_compra()
    {
        return $this->belongsTo(Carro_compra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
