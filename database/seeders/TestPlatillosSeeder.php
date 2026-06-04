<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaPlatillo;
use App\Models\Platillo;
use App\Models\Ingrediente;

class TestPlatillosSeeder extends Seeder
{
    public function run(): void
    {
        $catE = CategoriaPlatillo::firstOrCreate(['nombre' => 'ENTRADAS']);
        $catF = CategoriaPlatillo::firstOrCreate(['nombre' => 'PLATOS FUERTES']);
        $catG = CategoriaPlatillo::firstOrCreate(['nombre' => 'GUISADOS']);

        $p1 = Platillo::create(['nombre' => 'Crema de Champiñones', 'descripcion' => 'Deliciosa crema', 'precio' => 45, 'categoria_platillo_id' => $catE->id]);
        $p2 = Platillo::create(['nombre' => 'Lomo en Salsa de Ciruela', 'descripcion' => 'Lomo jugoso', 'precio' => 120, 'categoria_platillo_id' => $catF->id]);
        
        $g1 = Platillo::create(['nombre' => 'Tinga de Pollo', 'descripcion' => 'Clásica tinga', 'precio' => 60, 'categoria_platillo_id' => $catG->id]);
        $g2 = Platillo::create(['nombre' => 'Chicharrón en Salsa Verde', 'descripcion' => 'Chicharrón crujiente', 'precio' => 50, 'categoria_platillo_id' => $catG->id]);

        $i1 = Ingrediente::firstOrCreate(['nombre' => 'Champiñones', 'unidad' => 'kg', 'stock' => 5]);
        $i2 = Ingrediente::firstOrCreate(['nombre' => 'Crema', 'unidad' => 'l', 'stock' => 10]);
        $i3 = Ingrediente::firstOrCreate(['nombre' => 'Lomo de Cerdo', 'unidad' => 'kg', 'stock' => 2]);
        $i4 = Ingrediente::firstOrCreate(['nombre' => 'Ciruelas', 'unidad' => 'kg', 'stock' => 0.5]);
        $i5 = Ingrediente::firstOrCreate(['nombre' => 'Pollo deshebrado', 'unidad' => 'kg', 'stock' => 10]);
        $i6 = Ingrediente::firstOrCreate(['nombre' => 'Cebolla', 'unidad' => 'kg', 'stock' => 1]);

        $p1->ingredientes()->sync([
            $i1->id => ['cantidad_por_base' => 0.1, 'nota' => 'Limpios'],
            $i2->id => ['cantidad_por_base' => 0.05, 'nota' => 'Fresca']
        ]);

        $p2->ingredientes()->sync([
            $i3->id => ['cantidad_por_base' => 0.25, 'nota' => 'Corte grueso'],
            $i4->id => ['cantidad_por_base' => 0.05, 'nota' => 'Sin semilla']
        ]);

        $g1->ingredientes()->sync([
            $i5->id => ['cantidad_por_base' => 0.15, 'nota' => ''],
            $i6->id => ['cantidad_por_base' => 0.05, 'nota' => 'Julianas']
        ]);
    }
}
