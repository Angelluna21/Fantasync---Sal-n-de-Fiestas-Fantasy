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
            
            if (strpos($line, 'PLATILLO:') !== false) {
                // Extraer el nombre de todo lo que esté después de "PLATILLO:"
                $partes = explode('PLATILLO:', $line);
                $nombre = trim($partes[1] ?? '');
                $currentPlatillo = Platillo::firstOrNew(['nombre' => $nombre]);
                $currentPlatillo->precio = 0;
            } elseif (strpos($line, 'Categoría:') !== false) {
                $partes = explode('Categoría:', $line);
                $catNombre = trim($partes[1] ?? '');
                $categoria = CategoriaPlatillo::firstOrCreate(['nombre' => $catNombre]);
                if ($currentPlatillo) {
                    $currentPlatillo->categoria_platillo_id = $categoria->id;
                }
            } elseif (strpos($line, 'Descripción:') !== false) {
                $partes = explode('Descripción:', $line);
                $desc = trim($partes[1] ?? '');
                if ($currentPlatillo) {
                    $currentPlatillo->descripcion = $desc === 'Sin descripción' ? null : $desc;
                }
            } elseif (strpos($line, 'Precio Sugerido:') !== false) {
                $partes = explode('Precio Sugerido:', $line);
                $precioStr = trim($partes[1] ?? '');
                $precio = (float) preg_replace('/[^0-9.]/', '', $precioStr);
                if ($currentPlatillo) {
                    $currentPlatillo->precio = $precio;
                    $currentPlatillo->save();
                    
                    // Asegurar que el platillo esté disponible para el primer servicio gastronómico
                    // o para todos, para que aparezca en la vista de contratos.
                    $serviciosIds = \App\Models\ServicioGastronomico::pluck('id')->toArray();
                    if (!empty($serviciosIds)) {
                        $currentPlatillo->serviciosGastronomicos()->syncWithoutDetaching($serviciosIds);
                    }
                }
            } elseif (str_starts_with($line, '•')) {
                if ($currentPlatillo) {
                    // Limpiar caracteres extraños
                    $cleanLine = mb_convert_encoding($line, 'UTF-8', 'UTF-8');
                    preg_match('/•\s*(.+?)\s*:\s*([\d\.]+)\s*([a-zA-Z]+)?\s*(?:\[(.*?)\])?/', $cleanLine, $matches);
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
