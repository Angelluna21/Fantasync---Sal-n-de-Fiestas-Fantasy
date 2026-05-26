<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventoSalon extends Pivot
{
    protected $table = 'evento_salon';

    // 🔹 La magia: Conecta el salón del evento con los platillos que se van a servir (La Comanda)
    public function platillos(): BelongsToMany
    {
        return $this->belongsToMany(Platillo::class, 'evento_salon_platillo', 'evento_salon_id', 'platillo_id')
                    ->withPivot('porciones_plan', 'orden', 'notas')
                    ->withTimestamps();
    }
}