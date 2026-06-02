<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class SucursalSalonSeeder extends Seeder
{
    public function run(): void
    {
        // Se mantiene la sucursal Vicente Villada como sucursal base real
        $sucursal = Sucursal::query()->updateOrCreate(
            ['nombre' => 'Vicente Villada'],
            ['direccion' => 'San Rafael 254, Col. Vicente Villada, Nezahualcóyotl, Estado de México, C.P. 57710']
        );
    }
}
