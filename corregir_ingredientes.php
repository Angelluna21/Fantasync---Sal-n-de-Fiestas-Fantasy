<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Platillo;
use App\Models\Ingrediente;
use Illuminate\Support\Facades\DB;

// Limpiar tabla pivote para empezar de cero sin los ingredientes aleatorios (salchicha, etc)
DB::table('platillo_ingrediente')->truncate();

$recetas = [
    // ENTRADAS
    'Sopa Milpa' => ['Elote' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1], 'Calabaza' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1], 'Champiñón' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05]],
    'Crema Parmentier' => ['Papa' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.15], 'Puerro' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05], 'Crema' => ['unidad'=>'l', 'cat'=>'Lácteos', 'cant'=>0.05]],
    'Sopa Tarasca' => ['Frijol' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Tortilla' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Jitomate' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1]],
    'Crema Conde' => ['Frijol negro' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Crema' => ['unidad'=>'l', 'cat'=>'Lácteos', 'cant'=>0.05]],
    'Caldo Xochitl' => ['Pollo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.1], 'Garbanzo' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Aguacate' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05]],
    'Sopa Minestrone' => ['Pasta' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Jitomate' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1], 'Zanahoria' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05]],
    'Crema de Elote' => ['Elote' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.15], 'Crema' => ['unidad'=>'l', 'cat'=>'Lácteos', 'cant'=>0.05], 'Mantequilla' => ['unidad'=>'kg', 'cat'=>'Lácteos', 'cant'=>0.02]],
    'Crema Poblana' => ['Chile Poblano' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1], 'Crema' => ['unidad'=>'l', 'cat'=>'Lácteos', 'cant'=>0.05], 'Elote' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05]],
    'Sopa Azteca' => ['Tortilla' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Jitomate' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1], 'Queso Panela' => ['unidad'=>'kg', 'cat'=>'Lácteos', 'cant'=>0.05]],

    // FUERTES
    'Pollo Encacahuatado con Codito a la Hawaiana' => ['Pechuga de Pollo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Cacahuate' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Pasta Codito' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Piña' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05]],
    'Pollo Coloradito con Ensalada de Zanahoria' => ['Pechuga de Pollo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Pasta Mole Coloradito' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Zanahoria' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1]],
    'Suprema Cordon Bleu con Ensalada de la Casa' => ['Pechuga de Pollo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Jamón' => ['unidad'=>'kg', 'cat'=>'Carnes Frías', 'cant'=>0.05], 'Queso Manchego' => ['unidad'=>'kg', 'cat'=>'Lácteos', 'cant'=>0.05], 'Lechuga' => ['unidad'=>'pz', 'cat'=>'Frutas y Verduras', 'cant'=>0.1]],
    'Farfalle en salsa de tomate con Solomillo a las finas hierbas' => ['Pasta Farfalle' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Jitomate' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1], 'Solomillo de Cerdo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2]],
    'Lomo de Cerdo en Adobo con Gratinado de Papas' => ['Lomo de Cerdo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Chile Ancho' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.02], 'Papa' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.15], 'Queso Manchego' => ['unidad'=>'kg', 'cat'=>'Lácteos', 'cant'=>0.05]],
    'Cochinita Pibil con Arroz Blanco' => ['Carne de Cerdo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Achiote' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.02], 'Arroz' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1]],
    'Pollo en Salsa Poblana con Ensalada Griega y Arroz Blanco' => ['Pechuga de Pollo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Chile Poblano' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05], 'Arroz' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1]],
    'Fusilli a la Jardinera con Pechuga Cordon Bleu y Papas Gratinadas' => ['Pasta Fusilli' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Pechuga de Pollo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Papa' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1]],
    'Espaguetti Alfredo con Lomo en Adobo y Puré de Papa' => ['Pasta Espagueti' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Lomo de Cerdo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Papa' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.15], 'Crema' => ['unidad'=>'l', 'cat'=>'Lácteos', 'cant'=>0.05]],
    'Farfalle a la Bolognesa con Cerdo Asado y Ensalada de la Casa' => ['Pasta Farfalle' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Carne Molida de Res' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.1], 'Carne de Cerdo' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.15], 'Lechuga' => ['unidad'=>'pz', 'cat'=>'Frutas y Verduras', 'cant'=>0.1]],
    'Bistec a la Parrilla con Sopecitos' => ['Bisteck de Res' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.25], 'Masa de Maíz' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.1], 'Frijol' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05]],
    'Filete de Res en Salsa de Champiñones con Salteado de Verduras y Ensalada Griega' => ['Filete de Res' => ['unidad'=>'kg', 'cat'=>'Carnes', 'cant'=>0.2], 'Champiñón' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05], 'Verdura Mixta' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1]],

    // POSTRES
    'Pastel de Chocolate' => ['Harina' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Cacao' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.02], 'Azúcar' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05]],
    'Flan Napolitano' => ['Huevo' => ['unidad'=>'pz', 'cat'=>'Abarrotes', 'cant'=>1], 'Leche Condensada' => ['unidad'=>'l', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Vainilla' => ['unidad'=>'l', 'cat'=>'Abarrotes', 'cant'=>0.01]],
    'Tiramisú' => ['Queso Mascarpone' => ['unidad'=>'kg', 'cat'=>'Lácteos', 'cant'=>0.05], 'Café' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.01], 'Galleta' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05]],
    'Cheesecake con Frutos Rojos' => ['Queso Crema' => ['unidad'=>'kg', 'cat'=>'Lácteos', 'cant'=>0.1], 'Galleta' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.05], 'Frutos Rojos' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.05]],
    'Mousse de Mango' => ['Mango' => ['unidad'=>'kg', 'cat'=>'Frutas y Verduras', 'cant'=>0.1], 'Crema para batir' => ['unidad'=>'l', 'cat'=>'Lácteos', 'cant'=>0.05], 'Grenetina' => ['unidad'=>'kg', 'cat'=>'Abarrotes', 'cant'=>0.01]]
];

$platillos = Platillo::all();

foreach ($platillos as $p) {
    if (array_key_exists($p->nombre, $recetas)) {
        $ingredientesData = $recetas[$p->nombre];
        $syncData = [];
        foreach ($ingredientesData as $nombreIng => $info) {
            $ing = Ingrediente::firstOrCreate(
                ['nombre' => $nombreIng],
                ['unidad' => $info['unidad'], 'categoria' => $info['cat'], 'stock' => 0]
            );
            $syncData[$ing->id] = ['cantidad_por_base' => $info['cant']];
        }
        $p->ingredientes()->sync($syncData);
        echo "Platillo '{$p->nombre}' configurado con " . count($syncData) . " ingredientes reales.\n";
    }
}
echo "¡Corregido exitosamente!\n";
