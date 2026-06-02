<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('domicilio')->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->string('telefono_casa', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('ine_numero')->nullable(); // Clave para la validez legal del contrato
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};