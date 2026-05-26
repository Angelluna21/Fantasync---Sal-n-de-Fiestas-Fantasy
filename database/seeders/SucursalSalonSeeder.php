<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\Sucursal;
use Illuminate\Database\Seeder;

class SucursalSalonSeeder extends Seeder
{
    public function run(): void
    {
        $sucursal = Sucursal::query()->firstOrCreate(
            ['nombre' => 'Vicente Villada'],
            ['direccion' => 'San Rafael 254, Vicente Villada, Tlalpan']
        );

        Salon::query()->updateOrCreate(
            ['nombre' => 'San Rafael 254'],
            [
                'sucursal_id' => $sucursal->id,
                'alias' => 'SR254',
            ]
        );
    }
}
