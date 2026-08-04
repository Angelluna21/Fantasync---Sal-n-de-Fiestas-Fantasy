<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JugueteMovimiento extends Model
{
    protected $fillable = [
        'juguete_id',
        'contrato_id',
        'cantidad',
        'tipo'
    ];

    public function juguete()
    {
        return $this->belongsTo(Juguete::class);
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
}
