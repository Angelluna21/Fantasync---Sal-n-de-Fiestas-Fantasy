<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agrupamos platillos por nombre + categoría (los que se repiten)
        $grupos = DB::table('platillos')
            ->select('nombre', 'categoria_platillo_id')
            ->groupBy('nombre', 'categoria_platillo_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($grupos as $grupo) {
            $duplicados = DB::table('platillos')
                ->where('nombre', $grupo->nombre)
                ->where('categoria_platillo_id', $grupo->categoria_platillo_id)
                ->orderBy('id')
                ->get();

            // Nos quedamos con el más antiguo (el primero)
            $principal = $duplicados->first();
            $resto = $duplicados->slice(1);

            // 2. Enlazamos al principal con TODOS los tipos de servicio de sus duplicados
            foreach ($duplicados as $d) {
                $existe = DB::table('platillo_servicio_gastronomico')
                    ->where('platillo_id', $principal->id)
                    ->where('servicio_gastronomico_id', $d->servicio_gastronomico_id)
                    ->exists();

                if (!$existe) {
                    DB::table('platillo_servicio_gastronomico')->insert([
                        'platillo_id' => $principal->id,
                        'servicio_gastronomico_id' => $d->servicio_gastronomico_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 3. Trasladamos ingredientes de los duplicados hacia el principal (si no los tiene ya)
            foreach ($resto as $d) {
                $ingredientesDelDuplicado = DB::table('platillo_ingrediente')
                    ->where('platillo_id', $d->id)
                    ->get();

                foreach ($ingredientesDelDuplicado as $ing) {
                    $yaTiene = DB::table('platillo_ingrediente')
                        ->where('platillo_id', $principal->id)
                        ->where('ingrediente_id', $ing->ingrediente_id)
                        ->exists();

                    if (!$yaTiene) {
                        DB::table('platillo_ingrediente')->insert([
                            'platillo_id' => $principal->id,
                            'ingrediente_id' => $ing->ingrediente_id,
                            'cantidad_por_base' => $ing->cantidad_por_base,
                            'nota' => $ing->nota,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // 4. Borramos el platillo duplicado (esto borra en cascada su fila en platillo_ingrediente)
                DB::table('platillos')->where('id', $d->id)->delete();
            }
        }

        // 5. Los platillos que NO eran duplicados también necesitan su fila en la tabla pivote nueva
        $todos = DB::table('platillos')->get();
        foreach ($todos as $p) {
            $existe = DB::table('platillo_servicio_gastronomico')
                ->where('platillo_id', $p->id)
                ->where('servicio_gastronomico_id', $p->servicio_gastronomico_id)
                ->exists();

            if (!$existe) {
                DB::table('platillo_servicio_gastronomico')->insert([
                    'platillo_id' => $p->id,
                    'servicio_gastronomico_id' => $p->servicio_gastronomico_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Esta migración no se puede revertir de forma segura
        // porque involucra borrado de datos duplicados.
    }
};