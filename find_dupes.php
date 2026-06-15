<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$platillos = App\Models\Platillo::select('nombre', \DB::raw('count(*) as total'))
    ->groupBy('nombre')
    ->havingRaw('COUNT(*) > 1')
    ->get();

echo "=== PLATILLOS DUPLICADOS ===\n";
foreach ($platillos as $p) {
    echo "❌ '{$p->nombre}' ({$p->total} veces)\n";
    
    // Traer los IDs y si tienen ingredientes
    $dupes = App\Models\Platillo::where('nombre', $p->nombre)->get();
    foreach ($dupes as $d) {
        $ingCount = $d->ingredientes()->count();
        echo "   - ID: {$d->id} | Ingredientes: {$ingCount}\n";
    }
}
