<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ServicioGastronomico;
use App\Models\CategoriaPlatillo;
use App\Models\Ingrediente;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuario Admin
        User::query()->firstOrCreate(
            ['email' => 'admin@fantasync.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Llamada a seeders existentes
        $this->call([
            SucursalSalonSeeder::class,
            PlatillosFromRecetasSeeder::class,
        ]);

        // 3. Catálogos para FantaSync (Taquiza, 2 tiempos, etc.)
        ServicioGastronomico::firstOrCreate(['nombre' => 'Taquiza']);
        ServicioGastronomico::firstOrCreate(['nombre' => 'Dos Tiempos']);
        ServicioGastronomico::firstOrCreate(['nombre' => 'Tres Tiempos']);

        CategoriaPlatillo::firstOrCreate(['nombre' => 'Guisado', 'orden' => 1]);
        CategoriaPlatillo::firstOrCreate(['nombre' => 'Guarnición', 'orden' => 2]);
        CategoriaPlatillo::firstOrCreate(['nombre' => 'Entrada', 'orden' => 3]);

        // 4. Ingredientes Base (Aquí definimos las unidades reales)
        $ingredientes = [
            ['nombre' => 'Pollo', 'unidad' => 'kg'],
            ['nombre' => 'Limones', 'unidad' => 'kg'],
            ['nombre' => 'Sal', 'unidad' => 'kg'],
            ['nombre' => 'Aceite', 'unidad' => 'l'],
            ['nombre' => 'Cebolla', 'unidad' => 'kg'],
            ['nombre' => 'Tortillas', 'unidad' => 'pz'],
        ];

        foreach ($ingredientes as $ing) {
            Ingrediente::firstOrCreate(['nombre' => $ing['nombre']], ['unidad' => $ing['unidad']]);
        }
    }
}