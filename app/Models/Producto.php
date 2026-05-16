<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'stock', 'imagen', 'precio'];

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_prod');
    }
}