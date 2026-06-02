<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_completo',
        'domicilio',
        'codigo_postal',
        'telefono_casa',
        'celular',
        'correo_electronico',
        'ine_numero',
    ];

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
