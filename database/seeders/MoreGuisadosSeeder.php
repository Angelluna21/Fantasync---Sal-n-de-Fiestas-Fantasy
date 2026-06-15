<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaPlatillo;
use App\Models\Platillo;

class MoreGuisadosSeeder extends Seeder
{
    public function run(): void
    {
        $catG = CategoriaPlatillo::firstOrCreate(['nombre' => 'GUISADOS']);

        Platillo::firstOrCreate(['nombre' => 'Picadillo', 'categoria_platillo_id' => $catG->id], ['descripcion' => 'Picadillo casero', 'precio' => 50]);
        Platillo::firstOrCreate(['nombre' => 'Rajas con Crema', 'categoria_platillo_id' => $catG->id], ['descripcion' => 'Rajas poblanas', 'precio' => 45]);
        Platillo::firstOrCreate(['nombre' => 'Cochinita Pibil', 'categoria_platillo_id' => $catG->id], ['descripcion' => 'Cochinita tradicional', 'precio' => 70]);
        Platillo::firstOrCreate(['nombre' => 'Mole Poblano', 'categoria_platillo_id' => $catG->id], ['descripcion' => 'Mole con pollo', 'precio' => 65]);
        Platillo::firstOrCreate(['nombre' => 'Bistec a la Mexicana', 'categoria_platillo_id' => $catG->id], ['descripcion' => 'Bistec jugoso', 'precio' => 60]);
    }
}
