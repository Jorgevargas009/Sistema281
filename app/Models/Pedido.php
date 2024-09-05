<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['carro_compra_id', 'repartidor_id', 'direccion_id', 'fecha_pedido', 'fecha_entrega', 'estado_entrega', 'total'];

    public function carroCompra()
    {
        return $this->belongsTo(Carro_compra::class);
    }

    public function repartidor()
    {
        return $this->belongsTo(Repartidore::class);
    }

    public function direccion()
    {
        return $this->belongsTo(Direccione::class);
    }
}
