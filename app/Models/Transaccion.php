<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table = 'transacciones';
    protected $fillable = ['id_us', 'id_prod', 'cantidad', 'fecha', 'precio_unidad', 'total'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_us');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_prod');
    }
}
