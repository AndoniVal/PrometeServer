<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    // Laravel pluralizaría "Asignacion" como "asignacions" (en inglés),
    // así que le decimos el nombre real de la tabla
    protected $table = 'asignaciones';

    protected $fillable = ['google_event_id', 'google_event_titulo', 'id_mat', 'id_us'];

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_mat');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_us');
    }
}