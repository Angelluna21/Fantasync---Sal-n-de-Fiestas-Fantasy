<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = [
        'cliente',
        'correo',
        'telefono',
        'evento_fecha',
        'recepcion_hora',
        'inicio_hora',
        'tipo_evento',
        'festejado',
        'estado',
        'salon_id',
        'platillos',
        'extras',
        'total',
    ];

    protected $casts = [
        'evento_fecha' => 'date',
        'platillos' => 'array',
        'extras' => 'array',
        'total' => 'decimal:2',
    ];

    public function salon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
