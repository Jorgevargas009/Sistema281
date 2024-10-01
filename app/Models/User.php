<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre', 
        'apellido', 
        'email', 
        'telefono', 
        'password',
        'comunidad_id'
    ];

    public function comunidad()
    {
        return $this->belongsTo(Comunidade::class, 'comunidad_id');
    }
    
    // Relación con el modelo Producto
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    // Relación con direcciones
    public function direcciones()
    {
        return $this->hasMany(Direccione::class);
    }

    // Relación con notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacione::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
