<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platillo_servicio_gastronomico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platillo_id')->constrained('platillos')->onDelete('cascade');
            $table->foreignId('servicio_gastronomico_id')->constrained('servicios_gastronomicos')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['platillo_id', 'servicio_gastronomico_id'], 'platillo_servicio_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platillo_servicio_gastronomico');
    }
};