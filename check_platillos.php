<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categorias = \App\Models\Categoria::with('platillos')->get();

foreach ($categorias as $cat) {
    echo "Categoria: {$cat->nombre} (ID: {$cat->id})\n";
    foreach ($cat->platillos as $platillo) {
        echo "  - {$platillo->nombre} (ID: {$platillo->id})\n";
    }
}
