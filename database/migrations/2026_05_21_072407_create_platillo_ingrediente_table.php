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
        Schema::create('platillo_ingrediente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platillo_id')->constrained('platillos')->onDelete('cascade');
            $table->foreignId('ingrediente_id')->constrained('ingredientes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['platillo_id', 'ingrediente_id'], 'platillo_ingrediente_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platillo_ingrediente');
    }
};
