<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platillo;
use App\Models\Ingrediente;

class AttachIngredientsSeeder extends Seeder
{
    public function run(): void
    {
        $i1 = Ingrediente::firstOrCreate(['nombre' => 'Carne Molida', 'unidad' => 'kg', 'stock' => 5]);
        $i2 = Ingrediente::firstOrCreate(['nombre' => 'Jitomate', 'unidad' => 'kg', 'stock' => 8]);
        $i3 = Ingrediente::firstOrCreate(['nombre' => 'Cochinita', 'unidad' => 'kg', 'stock' => 4]);
        $i4 = Ingrediente::firstOrCreate(['nombre' => 'Pollo Entero', 'unidad' => 'kg', 'stock' => 10]);
        $i5 = Ingrediente::firstOrCreate(['nombre' => 'Bistec de Res', 'unidad' => 'kg', 'stock' => 6]);

        $platillos = Platillo::all();

        foreach ($platillos as $p) {
            if ($p->ingredientes()->count() === 0) {
                if (str_contains(strtolower($p->nombre), 'picadillo')) {
                    $p->ingredientes()->sync([$i1->id => ['cantidad_por_base' => 0.2], $i2->id => ['cantidad_por_base' => 0.1]]);
                } elseif (str_contains(strtolower($p->nombre), 'cochinita')) {
                    $p->ingredientes()->sync([$i3->id => ['cantidad_por_base' => 0.25]]);
                } elseif (str_contains(strtolower($p->nombre), 'mole')) {
                    $p->ingredientes()->sync([$i4->id => ['cantidad_por_base' => 0.3]]);
                } elseif (str_contains(strtolower($p->nombre), 'bistec')) {
                    $p->ingredientes()->sync([$i5->id => ['cantidad_por_base' => 0.25], $i2->id => ['cantidad_por_base' => 0.05]]);
                } else {
                    // Fallback
                    $p->ingredientes()->sync([$i2->id => ['cantidad_por_base' => 0.1]]);
                }
            }
        }
    }
}
