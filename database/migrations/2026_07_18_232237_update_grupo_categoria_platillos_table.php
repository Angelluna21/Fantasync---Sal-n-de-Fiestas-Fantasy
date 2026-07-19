<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $asignaciones = [
            // Grupo: Menús por Tiempos
            'Sopas, Cremas y Caldos' => ['grupo' => 'Menús por Tiempos', 'orden' => 10],
            'Pastas' => ['grupo' => 'Menús por Tiempos', 'orden' => 11],
            'Ensaladas' => ['grupo' => 'Menús por Tiempos', 'orden' => 12],
            'Plato Fuerte - Pollo' => ['grupo' => 'Menús por Tiempos', 'orden' => 13],
            'Plato Fuerte - Res' => ['grupo' => 'Menús por Tiempos', 'orden' => 14],
            'Plato Fuerte - Cerdo' => ['grupo' => 'Menús por Tiempos', 'orden' => 15],

            // Grupo: Opciones de Taquiza
            'Guisado - Pollo' => ['grupo' => 'Opciones de Taquiza', 'orden' => 20],
            'Guisado - Res' => ['grupo' => 'Opciones de Taquiza', 'orden' => 21],
            'Guisado - Cerdo' => ['grupo' => 'Opciones de Taquiza', 'orden' => 22],
            'Guisado - Vegetariano' => ['grupo' => 'Opciones de Taquiza', 'orden' => 23],
            'Guisado - Otros' => ['grupo' => 'Opciones de Taquiza', 'orden' => 24],

            // Grupo: Opciones de Parrillada
            'Carnes de Parrillada' => ['grupo' => 'Opciones de Parrillada', 'orden' => 30],

            // Grupo: Complementos Universales
            'Guarniciones' => ['grupo' => 'Complementos Universales', 'orden' => 40],
        ];

        foreach ($asignaciones as $nombre => $valores) {
            DB::table('categoria_platillos')
                ->where('nombre', $nombre)
                ->update($valores);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revertimos los valores, ya que podrían haber sido editados
        // manualmente después de aplicar esta migración.
    }
};