<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NominaDetalle extends Model
{
    protected $fillable = [
        'nomina_id',
        'evento_id',
        'fecha_trabajo',
        'puesto',
        'salario_base',
        'horas_extra',
        'subtotal',
    ];

    public function nomina()
    {
        return $this->belongsTo(Nomina::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
