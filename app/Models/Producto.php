<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = ['artesano_id', 'nombre', 'descripcion', 'precio', 'stock'];

    public function artesano()
    {
        return $this->belongsTo(Artesano::class);
    }

    public function reseña()
    {
        return $this->hasMany(Reseña::class);
    }
    public function promocione()
    {
        return $this->hasOne(Promocione::class);
    }
    public function detalle_compra()
    {
        return $this->hasMany(Detalle_Compra::class);
    }
}
