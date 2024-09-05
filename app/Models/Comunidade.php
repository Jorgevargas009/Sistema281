<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunidade extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'ubicacion', 'descripcion'];

    public function artesanos()
    {
        return $this->hasMany(Artesano::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
    public function repartidore()
    {
        return $this->hasMany(Repartidore::class);
    }
}
 