<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = ['id_us', 'id_mat', 'nombre_material', 'fecha'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_us');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_mat');
    }
}
