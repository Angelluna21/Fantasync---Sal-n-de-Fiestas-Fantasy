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
        Schema::create('juguete_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('juguete_id')->constrained()->onDelete('cascade');
            $table->foreignId('contrato_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('cantidad');
            $table->string('tipo')->default('salida'); // 'entrada' o 'salida'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juguete_movimientos');
    }
};
