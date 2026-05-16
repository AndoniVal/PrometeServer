<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoSaldo extends Model
{
    protected $table = 'movimientos_saldo';

    protected $fillable = [
        'id_us',
        'tipo',
        'cantidad',
        'comentario',
        'fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_us');
    }
}