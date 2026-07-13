<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('categorias', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Aquí guardaremos "Guisos", "Guarniciones", etc.
        $table->integer('orden'); // Aquí guardaremos el número de orden
        $table->timestamps(); // Esto Laravel lo usa para saber cuándo creaste/editaste algo
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
