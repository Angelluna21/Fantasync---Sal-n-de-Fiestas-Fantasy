<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaPlatillo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'grupo',
        'orden',
    ];

    public function platillos(): HasMany
    {
        return $this->hasMany(Platillo::class);
    }
}