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

    /**
     * Obtener las comandas (platillos por salón) directamente desde el contrato.
     */
    public function getComandasAttribute()
    {
        // Se asegura de cargar la relación de salones con sus platillos
        return $this->evento->eventoSalones()->with(['salon', 'platillos.categoriaPlatillo'])->get();
    }

    /**
     * Calcular los insumos requeridos para este contrato específico.
     */
    public function calcularInsumosRequeridos()
    {
        return app(\App\Services\CalculadoraInsumosService::class)->calcularParaEvento($this->evento);
    }
}
