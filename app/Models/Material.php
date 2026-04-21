<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiales';
    protected $fillable = ['id_us', 'nombre', 'tipo', 'estado'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_us');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'id_mat');
    }
}
