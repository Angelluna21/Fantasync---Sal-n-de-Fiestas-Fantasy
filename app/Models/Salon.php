<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['sucursal_id', 'nombre', 'alias'])]
class Salon extends Model
{
    use HasFactory;

    protected $table = 'salones';

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function eventos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'evento_salon')->withTimestamps();
    }
}
