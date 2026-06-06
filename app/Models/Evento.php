<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['fecha', 'estado', 'titulo', 'notas', 'cliente_id', 'hora_recepcion', 'hora_inicio', 'horas_duracion', 'tipo_evento', 'nombre_festejado', 'color_manteleria'])]
class Evento extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha' => 'date',
    ];

    public function salones(): BelongsToMany
    {
        
        return $this->belongsToMany(Salon::class, 'evento_salon')
                    ->using(EventoSalon::class)
                    ->withPivot('id', 'adultos', 'ninos', 'factor_nino')
                    ->withTimestamps();
    }

    public function eventoSalones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventoSalon::class, 'evento_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function contrato(): HasOne
    {
        return $this->hasOne(Contrato::class);
    }
}