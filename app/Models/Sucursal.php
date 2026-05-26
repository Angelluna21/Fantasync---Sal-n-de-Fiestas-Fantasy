<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre', 'direccion'])]
class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    public function salones(): HasMany
    {
        return $this->hasMany(Salon::class);
    }
}
