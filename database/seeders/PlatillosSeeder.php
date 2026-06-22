<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platillo;

class PlatillosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Platillo 1
        Platillo::create([
            'nombre' => 'Pechuga a la Cordon Bleu',
            'descripcion' => 'Pechuga de pollo rellena de jamón y queso, bañada en crema de champiñones.',
            'precio' => 150.00,
        ]);

        // Platillo 2
        Platillo::create([
            'nombre' => 'Lomo en Salsa de Ciruela',
            'descripcion' => 'Medallones de lomo de cerdo bañados en salsa agridulce de ciruela.',
            'precio' => 180.00,
        ]);

        // Platillo 3
        Platillo::create([
            'nombre' => 'Crema de Cilantro con Nuez',
            'descripcion' => 'Deliciosa crema caliente con un toque de cilantro fresco y trozos de nuez tostada.',
            'precio' => 85.00,
        ]);
        
        // Platillo 4
        Platillo::create([
            'nombre' => 'Pasta Alfredo con Pollo',
            'descripcion' => 'Fetuccini bañado en cremosa salsa Alfredo con queso parmesano y tiras de pollo asado.',
            'precio' => 120.00,
        ]);
    }
}
