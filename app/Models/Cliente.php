<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'comunidade_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comunidad()
    {
        return $this->belongsTo(Comunidade::class);
    }

    public function carrosCompra()
    {
        return $this->hasMany(Carro_compra::class);
    }
    public function soporte()
    {
        return $this->hasMany(Soporte::class);
    }
}
