<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['fecha', 'estado', 'titulo', 'notas'])]
class Evento extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha' => 'date',
    ];

    public function salones(): BelongsToMany
    {
        return $this->belongsToMany(Salon::class, 'evento_salon')->withTimestamps();
    }
}
