<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['nombre', 'edad', 'rol', 'password', 'email'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function materiales()
    {
        return $this->hasMany(Material::class, 'id_us');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_us');
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_us');
    }
}