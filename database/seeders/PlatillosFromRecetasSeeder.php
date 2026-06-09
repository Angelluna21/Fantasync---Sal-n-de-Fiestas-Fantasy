<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Platillo;
use App\Models\CategoriaPlatillo;
use App\Models\Ingrediente;
use Illuminate\Support\Facades\File;

class PlatillosFromRecetasSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('recetas.txt');
        if (!File::exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $currentPlatillo = null;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (str_starts_with($line, '🍽️  PLATILLO:')) {
                $nombre = trim(str_replace('🍽️  PLATILLO:', '', $line));
                $currentPlatillo = Platillo::firstOrNew(['nombre' => $nombre]);
                $currentPlatillo->precio = 0;
            } elseif (str_starts_with($line, '📁 Categoría:')) {
                $catNombre = trim(str_replace('📁 Categoría:', '', $line));
                $categoria = CategoriaPlatillo::firstOrCreate(['nombre' => $catNombre]);
                if ($currentPlatillo) {
                    $currentPlatillo->categoria_platillo_id = $categoria->id;
                }
            } elseif (str_starts_with($line, '📝 Descripción:')) {
                $desc = trim(str_replace('📝 Descripción:', '', $line));
                if ($currentPlatillo) {
                    $currentPlatillo->descripcion = $desc === 'Sin descripción' ? null : $desc;
                }
            } elseif (str_starts_with($line, '💵 Precio Sugerido:')) {
                $precioStr = trim(str_replace('💵 Precio Sugerido:', '', $line));
                $precio = (float) preg_replace('/[^0-9.]/', '', $precioStr);
                if ($currentPlatillo) {
                    $currentPlatillo->precio = $precio;
                    $currentPlatillo->save();
                }
            } elseif (str_starts_with($line, '•')) {
                if ($currentPlatillo) {
                    preg_match('/•\s*(.+?)\s*:\s*([\d\.]+)\s*([a-zA-Z]+)?\s*(?:\[(.*?)\])?/', $line, $matches);
                    if (count($matches) >= 3) {
                        $ingName = trim($matches[1]);
                        $ingQty = (float) $matches[2];
                        $ingUnit = isset($matches[3]) ? trim($matches[3]) : 'pz';
                        
                        $ingrediente = Ingrediente::firstOrCreate(
                            ['nombre' => $ingName],
                            ['unidad' => $ingUnit, 'stock' => 0]
                        );
                        
                        $currentPlatillo->ingredientes()->syncWithoutDetaching([
                            $ingrediente->id => ['cantidad_por_base' => $ingQty]
                        ]);
                    }
                }
            }
        }
    }
}
