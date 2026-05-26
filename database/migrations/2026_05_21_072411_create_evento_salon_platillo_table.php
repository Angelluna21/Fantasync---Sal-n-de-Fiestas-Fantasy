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
        Schema::create('evento_salon_platillo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('salon_id')->constrained('salones')->onDelete('cascade');
            $table->foreignId('platillo_id')->constrained('platillos')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['evento_id', 'salon_id', 'platillo_id'], 'evento_salon_platillo_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_salon_platillo');
    }
};
