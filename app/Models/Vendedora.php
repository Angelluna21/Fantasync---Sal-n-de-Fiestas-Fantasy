<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendedora extends Model
{
    /** @use HasFactory<\Database\Factories\VendedoraFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'estado',
    ];

    /**
     * Contratos en los que ha participado esta vendedora.
     */
    public function contratos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Contrato::class, 'contrato_vendedora')
                    ->withTimestamps();
    }
}
