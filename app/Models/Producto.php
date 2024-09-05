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

    public function reseñas()
    {
        return $this->hasMany(Reseña::class);
    }
    public function promocion()
    {
        return $this->hasOne(Promocione::class);
    }
}
