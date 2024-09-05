<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artesano extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'comunidade_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comunidade()
    {
        return $this->belongsTo(Comunidade::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
