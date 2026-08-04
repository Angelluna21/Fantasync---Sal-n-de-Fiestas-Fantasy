<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $fillable = [
        'nombre_empleado',
        'estado_pago',
        'pagos_extra',
        'monto_total',
        'metodo_pago',
        'observaciones',
    ];

    protected $casts = [
        'pagos_extra' => 'array',
    ];

    public function detalles()
    {
        return $this->hasMany(NominaDetalle::class);
    }
}
