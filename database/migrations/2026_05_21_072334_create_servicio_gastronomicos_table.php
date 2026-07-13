<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Agregado para poder insertar los datos

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tipos de servicio de comida que se ofrecen (ej. Taquiza, Menú por Tiempos).
        Schema::create('servicios_gastronomicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        // Insertamos los servicios base para el funcionamiento del sistema -->
        DB::table('servicios_gastronomicos')->insert([
            ['id' => 1, 'nombre' => 'Menús a 2 Tiempos'],
            ['id' => 2, 'nombre' => 'Menús a 3 Tiempos'],
            ['id' => 3, 'nombre' => 'Taquizas'],
            ['id' => 4, 'nombre' => 'Parrilladas'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // El nombre de la tabla es plural, siguiendo la convención de Laravel.
        Schema::dropIfExists('servicios_gastronomicos');
    }
};