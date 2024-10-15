<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = ['carro_compra_id', 'forma_pago','codigo', 'estado'];

    public function carroCompra()
    {
        return $this->belongsTo(Carro_compra::class);
    }
}
