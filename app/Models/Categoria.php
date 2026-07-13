<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // Esto le dice a Laravel qué campos pueden ser guardados desde el formulario
    protected $fillable = ['nombre', 'orden'];
}