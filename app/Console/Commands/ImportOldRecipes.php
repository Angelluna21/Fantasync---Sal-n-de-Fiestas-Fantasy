<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\CategoriaPlatillo;
use App\Models\Ingrediente;
use App\Models\Platillo;

class ImportOldRecipes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-old-recipes {--path= : Ruta absoluta al archivo cocina_fantasy.sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa categorías, platillos, ingredientes y recetas desde el volcado SQL del proyecto anterior (SalonFantasy).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ruta por defecto si no se especifica
        $defaultPath = 'C:\Users\ANGELANTONIOLUNARUIZ\OneDrive - UNIVERSIDAD TECNOLÓGICA DE NEZAHUALCÓYOTL\Escritorio\SalonFantasy\cocina_fantasy.sql';
        $path = $this->option('path') ?: $defaultPath;

        if (!file_exists($path)) {
            $this->error("No se encontró el archivo SQL en la ruta: {$path}");
            return Command::FAILURE;
        }

        $this->info("Leyendo archivo SQL: {$path}");
        $sqlContent = file_get_contents($path);

        $this->info("Iniciando la limpieza de las tablas actuales...");
        
        // Deshabilitar las llaves foráneas para poder hacer truncate o borrar sin problemas
        Schema::disableForeignKeyConstraints();

        // Borramos los datos actuales (Limpieza solicitada por el usuario)
        // Eliminamos las relaciones primero
        DB::table('platillo_ingrediente')->truncate();
        
        // Borramos los registros
        Platillo::truncate();
        Ingrediente::truncate();
        CategoriaPlatillo::truncate();
        
        $this->info("Tablas limpias.");

        // Parsea Categorías
        $this->info("Importando Categorías...");
        $categorias = $this->parseInsertValues($sqlContent, 'categoria_platillo');
        foreach ($categorias as $cat) {
            CategoriaPlatillo::insert([
                'id' => $cat[0],
                'nombre' => trim($cat[1], "'"),
                // El orden no es estrictamente necesario en el modelo por defecto, pero si lo tenemos
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line(" - " . count($categorias) . " categorías importadas.");

        // Parsea Ingredientes
        $this->info("Importando Ingredientes...");
        $ingredientes = $this->parseInsertValues($sqlContent, 'ingrediente');
        foreach ($ingredientes as $ing) {
            $unidadOriginal = strtolower(trim($ing[2], "'"));
            $unidadMapeada = 'pz'; // Por defecto pieza si es raro (manojo, lata, etc)
            
            if (in_array($unidadOriginal, ['kg', 'kilo'])) {
                $unidadMapeada = 'kg';
            } elseif (in_array($unidadOriginal, ['gr', 'g'])) {
                $unidadMapeada = 'gr';
            } elseif (in_array($unidadOriginal, ['l', 'litro', 'litros', 'botellas'])) {
                $unidadMapeada = 'l';
            } elseif ($unidadOriginal === 'ml') {
                $unidadMapeada = 'ml';
            }

            Ingrediente::insert([
                'id' => $ing[0],
                'nombre' => trim($ing[1], "'"),
                'unidad' => $unidadMapeada,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line(" - " . count($ingredientes) . " ingredientes importados.");

        // Parsea Platillos
        $this->info("Importando Platillos...");
        $platillos = $this->parseInsertValues($sqlContent, 'platillo');
        foreach ($platillos as $plat) {
            // El campo id_categoria viene en la posición 3, porciones_base en la 4
            // A veces el id_categoria puede ser NULL.
            $categoria_id = strtoupper(trim($plat[3])) === 'NULL' ? null : $plat[3];
            
            Platillo::insert([
                'id' => $plat[0],
                'nombre' => trim($plat[1], "'"),
                'descripcion' => trim($plat[2], "'"),
                'categoria_platillo_id' => $categoria_id,
                'porciones_base' => $plat[4] ?? 100, // Por defecto 100 si no viene
                // asumiendo que no tienen un servicio gastronomico asginado
                'servicio_gastronomico_id' => null, 
                'precio' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line(" - " . count($platillos) . " platillos importados.");

        // Parsea Recetas (Tabla pivote)
        $this->info("Importando Recetas (Relaciones Ingrediente - Platillo)...");
        $recetas = $this->parseInsertValues($sqlContent, 'receta');
        $pivotesCount = 0;
        foreach ($recetas as $rec) {
            // id_platillo, id_ingrediente, cantidad_por_base, nota
            $id_platillo = $rec[0];
            $id_ingrediente = $rec[1];
            $cantidad_por_base = $rec[2];
            
            // Validamos que el platillo y el ingrediente existan para no romper llaves foráneas
            if (Platillo::where('id', $id_platillo)->exists() && Ingrediente::where('id', $id_ingrediente)->exists()) {
                DB::table('platillo_ingrediente')->insert([
                    'platillo_id' => $id_platillo,
                    'ingrediente_id' => $id_ingrediente,
                    'cantidad_por_base' => $cantidad_por_base,
                    'nota' => isset($rec[3]) && strtoupper(trim($rec[3])) !== 'NULL' ? trim($rec[3], "'") : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $pivotesCount++;
            }
        }
        $this->line(" - " . $pivotesCount . " recetas importadas.");

        Schema::enableForeignKeyConstraints();

        $this->info("¡Migración completada exitosamente!");
        return Command::SUCCESS;
    }

    /**
     * Extrae los valores de un bloque INSERT INTO `tabla` (...) VALUES (...)
     * de un dump de phpMyAdmin.
     */
    private function parseInsertValues($sqlContent, $tableName)
    {
        $matches = [];
        // Buscar el patrón INSERT INTO `tableName` (...) VALUES ...;
        // El bloque puede abarcar múltiples líneas
        $pattern = "/INSERT INTO `{$tableName}`[^\)]+\) VALUES\s*([\s\S]*?);/im";
        
        if (preg_match($pattern, $sqlContent, $matches)) {
            $valuesString = $matches[1];
            
            // Reemplazar saltos de línea para que todo esté en una línea
            $valuesString = str_replace(["\r", "\n"], '', $valuesString);
            
            // Extraer las tuplas (..., ..., ...)
            // Esta expresión regular mejorada maneja las comas dentro de las comillas simples
            preg_match_all("/\((.*?)\)(?:,|$)/", $valuesString, $tuples);
            
            $results = [];
            foreach ($tuples[1] as $tuple) {
                // Separar por comas, respetando lo que está dentro de comillas simples
                $elements = str_getcsv($tuple, ",", "'");
                // Limpiar espacios en blanco
                $elements = array_map('trim', $elements);
                $results[] = $elements;
            }
            
            return $results;
        }
        
        return [];
    }
}
