<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro_compra extends Model
{
    use HasFactory;
    protected $fillable = ['cliente_id', 'total', 'fecha_creacion'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalleCompras()
    {
        return $this->hasMany(Detalle_compra::class);
    }
}
