<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaPlatillo;

class UpdateCategoriasSeeder extends Seeder
{
    public function run()
    {
        CategoriaPlatillo::where('nombre', 'Guisados')->update(['grupo' => 'Taquiza / Parrillada']);
        CategoriaPlatillo::where('nombre', 'Entradas')->update(['grupo' => '2 y 3 Tiempos']);
        CategoriaPlatillo::where('nombre', 'Platos Fuertes')->update(['grupo' => '2 y 3 Tiempos']);
        CategoriaPlatillo::where('nombre', 'Menú Infantil')->update(['grupo' => 'Menú Infantil']);
        CategoriaPlatillo::where('nombre', 'Buffet Infantil')->update(['grupo' => 'Menú Infantil']);
        
        CategoriaPlatillo::where('nombre', 'Bebidas')->update(['grupo' => 'Bebidas']);
        CategoriaPlatillo::where('nombre', 'Dulces')->update(['grupo' => 'Complementos']);

        $cat = CategoriaPlatillo::where('nombre', 'Guarniciones')->first();
        if ($cat) { 
            $cat->nombre = 'Guarniciones (Taquiza)';
            $cat->grupo = 'Taquiza / Parrillada'; 
            $cat->save(); 
        }

        CategoriaPlatillo::firstOrCreate(['nombre' => 'Guarniciones (Formales)'], [
            'orden' => 4,
            'grupo' => '2 y 3 Tiempos'
        ]);

        CategoriaPlatillo::firstOrCreate(['nombre' => 'Cremas y Sopas'], [
            'orden' => 1,
            'grupo' => '2 y 3 Tiempos'
        ]);

        CategoriaPlatillo::firstOrCreate(['nombre' => 'Parrillada (Carnes)'], [
            'orden' => 2,
            'grupo' => 'Taquiza / Parrillada'
        ]);
        
        // Remove Postres if it exists
        CategoriaPlatillo::where('nombre', 'Postres')->delete();
    }
}
