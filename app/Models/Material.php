<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiales';
    protected $fillable = ['id_us', 'id_prestado', 'nombre', 'descripcion', 'tipo', 'estado', 'imagen'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_us');
    }

    public function usuarioPrestado()
    {
        return $this->belongsTo(User::class, 'id_prestado');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_mat');
    }
}