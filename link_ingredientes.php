<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Platillo;
use App\Models\Ingrediente;

$file = __DIR__.'/recetas.txt';
if (!file_exists($file)) {
    die("No existe recetas.txt");
}

$content = file_get_contents($file);
$blocks = explode('---------------------------------------------------------------------', $content);

$platillos = Platillo::all();
$matched = 0;

foreach ($blocks as $block) {
    if (preg_match('/🍽️\s*PLATILLO:\s*(.*)/', $block, $matchNombre)) {
        $nombreReceta = trim($matchNombre[1]);
        
        // Buscar el platillo en la BD
        $platillo = Platillo::where('nombre', 'like', "%{$nombreReceta}%")->first();
        if ($platillo) {
            echo "Encontrado en BD: " . $platillo->nombre . "\n";
            $matched++;
            
            // Buscar ingredientes
            if (preg_match('/🌿 Ingredientes Requeridos:(.*?)(?:🍽️|-------------------|$)/s', $block, $matchIngredientes)) {
                $lineas = explode("\n", trim($matchIngredientes[1]));
                $syncData = [];
                
                foreach ($lineas as $linea) {
                    if (preg_match('/•\s*(.*?)\s*:\s*([\d\.]+)\s*(\w+)\s*\[(.*?)\]/', $linea, $m)) {
                        $ingNombre = trim($m[1]);
                        $cantidad = (float)$m[2];
                        $unidad = trim($m[3]);
                        $categoria = trim($m[4]);
                        
                        // Crear o buscar ingrediente
                        $ing = Ingrediente::firstOrCreate(
                            ['nombre' => $ingNombre],
                            ['unidad' => $unidad, 'categoria' => $categoria, 'stock' => 0] // stock 0
                        );
                        
                        $syncData[$ing->id] = ['cantidad_por_base' => $cantidad];
                    }
                }
                
                if (count($syncData) > 0) {
                    $platillo->ingredientes()->sync($syncData);
                    echo "  -> " . count($syncData) . " ingredientes vinculados.\n";
                }
            }
        }
    }
}

echo "Se encontraron y vincularon recetas para $matched platillos.\n";

// Si hay platillos sin ingredientes (porque no estaban en recetas.txt), les ponemos algunos genéricos para la demo
$sinIngredientes = Platillo::doesntHave('ingredientes')->get();
$ingGenericos = Ingrediente::inRandomOrder()->limit(3)->get();
foreach ($sinIngredientes as $p) {
    $syncData = [];
    foreach ($ingGenericos as $ig) {
        $syncData[$ig->id] = ['cantidad_por_base' => rand(1, 5) * 0.5];
    }
    $p->ingredientes()->sync($syncData);
    echo "Platillo sin receta exacta '{$p->nombre}' -> asignados 3 ingredientes genéricos para la demo.\n";
}
echo "Proceso terminado.\n";
