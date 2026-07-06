<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $fillable = [
        'nombre_empleado',
        'puesto',
        'salario_base',
        'horas_extra',
        'fecha_trabajo',
        'evento_id',
        'estado_pago',
        'monto_total',
        'metodo_pago',
        'observaciones',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
