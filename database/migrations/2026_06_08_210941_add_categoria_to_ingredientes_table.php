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
        Schema::table('ingredientes', function (Blueprint $table) {
            $table->string('categoria', 50)->default('Abarrotes');
        });

        // Categorizar ingredientes existentes en base a palabras clave
        $categorias = [
            'Frutas y Verduras' => [
                'limon', 'cebolla', 'jitomate', 'champiñon', 'ciruela', 'aguacate', 'cilantro', 
                'ajo', 'papa', 'zanahoria', 'chile', 'lechuga', 'tomate', 'verdura', 'fruta'
            ],
            'Cremería' => [
                'crema', 'queso', 'leche', 'mantequilla', 'yogur', 'cremeria', 'lacteo'
            ],
            'Carnes' => [
                'pollo', 'cochinita', 'bistec', 'res', 'carne', 'lomo', 'cerdo', 'tocino', 
                'jamon', 'salchicha', 'pechuga', 'deshebrada', 'molida', 'puerco'
            ],
            'Abarrotes' => [
                'sal', 'aceite', 'tortilla', 'pimienta', 'azucar', 'frijol', 'arroz', 
                'harina', 'pan', 'tostada', 'salsa', 'vinagre', 'pasta', 'lata', 'consome'
            ]
        ];

        foreach ($categorias as $cat => $keywords) {
            foreach ($keywords as $kw) {
                DB::table('ingredientes')
                    ->where(DB::raw('lower(nombre)'), 'like', "%{$kw}%")
                    ->update(['categoria' => $cat]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredientes', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
