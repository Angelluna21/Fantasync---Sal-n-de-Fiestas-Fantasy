<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = [
        'evento_id',
        'bebidas',
        'servicios_extras',
        'monto_total',
        'anticipo',
        'saldo_pendiente',
        'consentimiento_imagen',
        'fecha_firma',
    ];

    protected $casts = [
        'bebidas' => 'array',
        'servicios_extras' => 'array',
        'monto_total' => 'decimal:2',
        'anticipo' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'consentimiento_imagen' => 'boolean',
        'fecha_firma' => 'date',
    ];

    public function evento(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }
}
