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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->enum('estado', ['cotizacion', 'confirmado', 'finalizado', 'cancelado'])->default('cotizacion');
            $table->string('titulo', 150);
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['fecha', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};