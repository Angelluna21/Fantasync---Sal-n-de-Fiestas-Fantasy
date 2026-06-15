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
        // 1. Script para estandarizar la tabla platillo_ingrediente a 100 porciones.
        // Formula: nueva_cantidad_por_base = (cantidad_por_base / porciones_base) * 100
        $platillos = DB::table('platillos')->get();
        foreach ($platillos as $platillo) {
            $porcionesBase = $platillo->porciones_base ?: 1;
            
            if ($porcionesBase != 100) {
                // Actualizar todos los ingredientes de este platillo
                $ingredientes = DB::table('platillo_ingrediente')
                    ->where('platillo_id', $platillo->id)
                    ->get();
                
                foreach ($ingredientes as $ingrediente) {
                    $nuevaCantidad = ($ingrediente->cantidad_por_base / $porcionesBase) * 100;
                    
                    DB::table('platillo_ingrediente')
                        ->where('id', $ingrediente->id)
                        ->update(['cantidad_por_base' => $nuevaCantidad]);
                }
            }
        }

        // 2. Eliminar la columna 'porciones_base' de la tabla 'platillos'
        Schema::table('platillos', function (Blueprint $table) {
            $table->dropColumn('porciones_base');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Añadir la columna de vuelta con su valor por defecto
        Schema::table('platillos', function (Blueprint $table) {
            $table->integer('porciones_base')->default(100)->after('precio');
        });
        
        // No es posible revertir de forma exacta los valores originales de cantidad_por_base
        // a menos que los hubiéramos guardado. Aquí asumimos que la base es 100 por defecto.
    }
};
