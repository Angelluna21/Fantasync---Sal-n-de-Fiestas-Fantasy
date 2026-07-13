<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Traemos todos los ingredientes y los agrupamos por nombre normalizado
        $todos = DB::table('ingredientes')->orderBy('id')->get();

        $grupos = $todos->groupBy(function ($ingrediente) {
            return strtolower(trim($ingrediente->nombre));
        })->filter(function ($grupo) {
            return $grupo->count() > 1;
        });

        foreach ($grupos as $grupo) {
            $principal = $grupo->first(); // el más antiguo, el que se queda
            $resto = $grupo->slice(1);

            foreach ($resto as $duplicado) {
                // 2. Buscamos todas las relaciones platillo_ingrediente del duplicado
                $relaciones = DB::table('platillo_ingrediente')
                    ->where('ingrediente_id', $duplicado->id)
                    ->get();

                foreach ($relaciones as $rel) {
                    // ¿El platillo principal ya tiene este ingrediente enlazado?
                    $yaExiste = DB::table('platillo_ingrediente')
                        ->where('platillo_id', $rel->platillo_id)
                        ->where('ingrediente_id', $principal->id)
                        ->exists();

                    if ($yaExiste) {
                        // Ya existe la relación con el principal, solo borramos la del duplicado
                        DB::table('platillo_ingrediente')->where('id', $rel->id)->delete();
                    } else {
                        // No existe, actualizamos la relación para que apunte al principal
                        DB::table('platillo_ingrediente')
                            ->where('id', $rel->id)
                            ->update(['ingrediente_id' => $principal->id]);
                    }
                }

                // 3. Borramos el insumo duplicado
                DB::table('ingredientes')->where('id', $duplicado->id)->delete();
            }
        }
    }

    public function down(): void
    {
        // No reversible de forma segura
    }
};