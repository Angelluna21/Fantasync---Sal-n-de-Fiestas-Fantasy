<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platillo;
use App\Models\ServicioGastronomico;
use App\Models\CategoriaPlatillo; // Agregamos el modelo de categoría

class PlatillosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos la categoría de prueba
        $categoria = CategoriaPlatillo::create([
            'nombre' => 'Plato Fuerte', 
        ]);

        // 2. Creamos el servicio
        $servicio = ServicioGastronomico::create([
            'nombre' => 'Banquete Estándar',
        ]);

        // 3. Registramos los platillos amarrados a la categoría y al servicio
        $p1 = Platillo::create([
            'categoria_platillo_id' => $categoria->id,
            'nombre' => 'Pechuga a la Cordon Bleu',
            'descripcion' => 'Pechuga de pollo rellena de jamón y queso, bañada en crema de champiñones.',
            'precio' => 150.00,
        ]);
        $p1->serviciosGastronomicos()->sync([$servicio->id]);

        $p2 = Platillo::create([
            'categoria_platillo_id' => $categoria->id,
            'nombre' => 'Lomo en Salsa de Ciruela',
            'descripcion' => 'Medallones de lomo de cerdo bañados en salsa agridulce de ciruela.',
            'precio' => 180.00,
        ]);
        $p2->serviciosGastronomicos()->sync([$servicio->id]);

        $p3 = Platillo::create([
            'categoria_platillo_id' => $categoria->id,
            'nombre' => 'Crema de Cilantro con Nuez',
            'descripcion' => 'Deliciosa crema caliente con un toque de cilantro fresco y trozos de nuez tostada.',
            'precio' => 85.00,
        ]);
        $p3->serviciosGastronomicos()->sync([$servicio->id]);
        
        $p4 = Platillo::create([
            'categoria_platillo_id' => $categoria->id,
            'nombre' => 'Pasta Alfredo con Pollo',
            'descripcion' => 'Fetuccini bañado en cremosa salsa Alfredo con queso parmesano y tiras de pollo asado.',
            'precio' => 120.00,
        ]);
        $p4->serviciosGastronomicos()->sync([$servicio->id]);
    }
}