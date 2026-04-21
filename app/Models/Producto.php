<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'tipo', 'stock'];

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_prod');
    }
}