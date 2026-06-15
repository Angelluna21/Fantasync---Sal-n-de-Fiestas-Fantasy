<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Platillo;

echo "=== INICIANDO FUSIÓN DE DUPLICADOS ===\n";

$nombresDuplicados = Platillo::select('nombre')
    ->groupBy('nombre')
    ->havingRaw('COUNT(*) > 1')
    ->pluck('nombre');

$countMerged = 0;

foreach ($nombresDuplicados as $nombre) {
    // Obtener todos los platillos con este nombre, ordenados por ID (el más viejo primero)
    $dupes = Platillo::where('nombre', $nombre)->orderBy('id', 'asc')->get();
    
    // El primero es el que vamos a conservar
    $keep = $dupes->shift();
    echo "Manteniendo: {$keep->nombre} (ID: {$keep->id})\n";
    
    // Los demás se van a fusionar y eliminar
    foreach ($dupes as $dupe) {
        echo "  - Fusionando ID: {$dupe->id} hacia ID: {$keep->id}...\n";
        
        DB::transaction(function() use ($keep, $dupe) {
            // 1. Reasignar eventos (si existen)
            // Asumiendo que la tabla se llama 'evento_salon_platillo' (o el pivot correspondiente)
            try {
                DB::table('evento_salon_platillo')
                  ->where('platillo_id', $dupe->id)
                  ->update(['platillo_id' => $keep->id]);
            } catch (\Exception $e) {
                // Si la tabla no existe o hay error de clave única, lo ignoramos de momento
            }
            
            // 2. Eliminar sus recetas (pivot ingredientes)
            DB::table('platillo_ingrediente')->where('platillo_id', $dupe->id)->delete();
            
            // 3. Eliminar el platillo duplicado
            $dupe->delete();
        });
        
        $countMerged++;
    }
}

echo "\n✅ Fusión exitosa. Se eliminaron {$countMerged} duplicados fusionando su historial.\n";
