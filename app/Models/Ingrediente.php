<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['nombre', 'unidad', 'categoria'])]
class Ingrediente extends Model
{
    use HasFactory;

    public function platillos(): BelongsToMany
    {
        return $this->belongsToMany(Platillo::class, 'platillo_ingrediente')->withTimestamps();
    }
}
