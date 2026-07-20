<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$postres = ['Pastel de Chocolate', 'Flan Napolitano', 'Tiramisú', 'Cheesecake con Frutos Rojos', 'Mousse de Mango'];
foreach ($postres as $postre) {
    App\Models\Platillo::firstOrCreate([
        'nombre' => $postre,
        'categoria_platillo_id' => 33
    ], [
        'descripcion' => 'Agregado desde el catálogo PDF de 2 y 3 tiempos',
        'precio' => 0
    ]);
}
echo "Postres insertados.\n";
