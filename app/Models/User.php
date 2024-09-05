<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    

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
        'password'
    ];

    // Relación con administradores
    public function administradore()
    {
        return $this->hasOne(Administradore::class);
    }

    // Relación con artesanos
    public function artesano()
    {
        return $this->hasOne(Artesano::class);
    }

    // Relación con clientes
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    // Relación con repartidores
    public function repartidore()
    {
        return $this->hasOne(Repartidore::class);
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
