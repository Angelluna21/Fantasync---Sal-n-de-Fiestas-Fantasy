<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Platillo;

$entradas = [
    'Sopa Milpa',
    'Crema Parmentier',
    'Sopa Tarasca',
    'Crema Conde',
    'Caldo Xochitl',
    'Sopa Minestrone',
    'Crema de Elote',
    'Crema Poblana',
    'Sopa Azteca'
];

$fuertes = [
    'Pollo Encacahuatado con Codito a la Hawaiana',
    'Pollo Coloradito con Ensalada de Zanahoria',
    'Suprema Cordon Bleu con Ensalada de la Casa',
    'Farfalle en salsa de tomate con Solomillo a las finas hierbas',
    'Lomo de Cerdo en Adobo con Gratinado de Papas',
    'Cochinita Pibil con Arroz Blanco',
    'Pollo en Salsa Poblana con Ensalada Griega y Arroz Blanco',
    'Fusilli a la Jardinera con Pechuga Cordon Bleu y Papas Gratinadas',
    'Espaguetti Alfredo con Lomo en Adobo y Puré de Papa',
    'Farfalle a la Bolognesa con Cerdo Asado y Ensalada de la Casa',
    'Bistec a la Parrilla con Sopecitos',
    'Filete de Res en Salsa de Champiñones con Salteado de Verduras y Ensalada Griega'
];

echo "Insertando Entradas...\n";
foreach ($entradas as $nombre) {
    $platillo = Platillo::firstOrCreate([
        'nombre' => $nombre,
        'categoria_platillo_id' => 31 // Entradas
    ], [
        'descripcion' => 'Agregado desde el catálogo PDF de 2 y 3 tiempos',
        'precio' => 0
    ]);
    echo " -> {$platillo->nombre}\n";
}

echo "\nInsertando Platos Fuertes...\n";
foreach ($fuertes as $nombre) {
    $platillo = Platillo::firstOrCreate([
        'nombre' => $nombre,
        'categoria_platillo_id' => 32 // Platos Fuertes
    ], [
        'descripcion' => 'Agregado desde el catálogo PDF de 2 y 3 tiempos',
        'precio' => 0
    ]);
    echo " -> {$platillo->nombre}\n";
}
