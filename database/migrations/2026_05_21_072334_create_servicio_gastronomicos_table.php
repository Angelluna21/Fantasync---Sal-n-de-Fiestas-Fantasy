<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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