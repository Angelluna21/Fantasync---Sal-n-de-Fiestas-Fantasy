<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioGastronomico extends Model
{
    use HasFactory;

    protected $table = 'servicios_gastronomicos';


    protected $fillable = [
        'nombre'
    ];
}