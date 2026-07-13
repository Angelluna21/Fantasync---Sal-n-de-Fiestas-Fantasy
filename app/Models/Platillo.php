<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Platillo extends Model
{
    use HasFactory;

    protected $table = 'platillos';

    protected $fillable = [
        'categoria_platillo_id',
        'nombre',
        'descripcion',
        'precio'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function categoriaPlatillo(): BelongsTo
    {
        return $this->belongsTo(CategoriaPlatillo::class);
    }

    public function serviciosGastronomicos(): BelongsToMany
    {
        return $this->belongsToMany(ServicioGastronomico::class, 'platillo_servicio_gastronomico')
                    ->withTimestamps();
    }

    public function ingredientes(): BelongsToMany
    {
        return $this->belongsToMany(Ingrediente::class, 'platillo_ingrediente')
                    ->withPivot('cantidad_por_base', 'nota')
                    ->withTimestamps();
    }
}