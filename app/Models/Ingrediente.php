<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingrediente extends Model
{
    use HasFactory;

    // Aquí está la línea clave que permite guardar el insumo desde tu modal
    protected $fillable = [
        'nombre', 
        'unidad', 
        'categoria'
    ];

    public function platillos(): BelongsToMany
    {
        return $this->belongsToMany(Platillo::class, 'platillo_ingrediente')->withTimestamps();
    }
}