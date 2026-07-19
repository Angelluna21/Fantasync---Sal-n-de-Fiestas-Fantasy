<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Agrega el campo "grupo" para poder mostrar encabezados
        //    como "Menús por Tiempos", "Opciones de Taquiza", etc.
        if (!Schema::hasColumn('categoria_platillos', 'grupo')) {
            Schema::table('categoria_platillos', function (Blueprint $table) {
                $table->string('grupo', 60)->nullable()->after('nombre');
            });
        }

        // 2. Siembra las categorías base (solo si la tabla está vacía,
        //    para no duplicar si ya la habías llenado a mano).
        if (DB::table('categoria_platillos')->count() === 0) {
            DB::table('categoria_platillos')->insert([
                // Grupo: Menús por Tiempos
                ['nombre' => 'Sopas, Cremas y Caldos', 'grupo' => 'Menús por Tiempos', 'orden' => 10, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Pastas', 'grupo' => 'Menús por Tiempos', 'orden' => 11, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Ensaladas', 'grupo' => 'Menús por Tiempos', 'orden' => 12, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Plato Fuerte - Pollo', 'grupo' => 'Menús por Tiempos', 'orden' => 13, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Plato Fuerte - Res', 'grupo' => 'Menús por Tiempos', 'orden' => 14, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Plato Fuerte - Cerdo', 'grupo' => 'Menús por Tiempos', 'orden' => 15, 'created_at' => now(), 'updated_at' => now()],

                // Grupo: Opciones de Taquiza
                ['nombre' => 'Guisado - Pollo', 'grupo' => 'Opciones de Taquiza', 'orden' => 20, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Guisado - Res', 'grupo' => 'Opciones de Taquiza', 'orden' => 21, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Guisado - Cerdo', 'grupo' => 'Opciones de Taquiza', 'orden' => 22, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Guisado - Vegetariano', 'grupo' => 'Opciones de Taquiza', 'orden' => 23, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Guisado - Otros', 'grupo' => 'Opciones de Taquiza', 'orden' => 24, 'created_at' => now(), 'updated_at' => now()],

                // Grupo: Opciones de Parrillada
                ['nombre' => 'Carnes de Parrillada', 'grupo' => 'Opciones de Parrillada', 'orden' => 30, 'created_at' => now(), 'updated_at' => now()],

                // Grupo: Complementos Universales
                ['nombre' => 'Guarniciones', 'grupo' => 'Complementos Universales', 'orden' => 40, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categoria_platillos', 'grupo')) {
            Schema::table('categoria_platillos', function (Blueprint $table) {
                $table->dropColumn('grupo');
            });
        }
    }
};