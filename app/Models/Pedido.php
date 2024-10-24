<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['carro_compra_id', 'user_id', 'direccione_id', 'fecha_pedido', 'fecha_entrega', 'estado_entrega', 'total'];

    public function carro_compra()
    {
        return $this->belongsTo(Carro_compra::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function direccion()
    {
        return $this->belongsTo(Direccione::class, 'direccione_id');
    }
}
