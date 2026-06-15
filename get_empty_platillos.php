<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$platillosSinReceta = App\Models\Platillo::doesntHave('ingredientes')
    ->with('categoriaPlatillo')
    ->orderBy('categoria_id')
    ->orderBy('nombre')
    ->get();

$md = "# ⚠️ Reporte de Platillos sin Receta (Sin Ingredientes)\n\n";
$md .= "Los siguientes **" . $platillosSinReceta->count() . "** platillos están dados de alta en el sistema, pero no tienen ningún ingrediente registrado. Si se seleccionan en un contrato o comanda, no generarán requerimientos de compra.\n\n";

$categoriaActual = null;

foreach ($platillosSinReceta as $platillo) {
    $catNombre = $platillo->categoriaPlatillo ? $platillo->categoriaPlatillo->nombre : 'Sin Categoría';
    
    if ($catNombre !== $categoriaActual) {
        $md .= "### " . $catNombre . "\n";
        $categoriaActual = $catNombre;
    }
    
    $md .= "- [ ] " . $platillo->nombre . "\n";
}

file_put_contents('platillos_incompletos.md', $md);
echo "Archivo platillos_incompletos.md generado exitosamente.\n";
