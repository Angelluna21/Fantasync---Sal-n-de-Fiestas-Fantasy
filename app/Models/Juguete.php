<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Juguete extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'stock_actual',
        'stock_minimo'
    ];

    public function movimientos()
    {
        return $this->hasMany(JugueteMovimiento::class);
    }
}
