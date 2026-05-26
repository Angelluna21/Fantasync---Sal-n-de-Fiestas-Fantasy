<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['categoria_platillo_id', 'nombre', 'precio'])]
class Platillo extends Model
{
    use HasFactory;

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoriaPlatillo(): BelongsTo
    {
        return $this->belongsTo(CategoriaPlatillo::class);
    }

    public function ingredientes(): BelongsToMany
    {
        return $this->belongsToMany(Ingrediente::class, 'platillo_ingrediente')->withTimestamps();
    }
}
