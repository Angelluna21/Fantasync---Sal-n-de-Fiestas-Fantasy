<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si por alguna razón la tabla se creó con evento_id y salon_id, la tiramos
        // y la volvemos a crear de forma correcta con evento_salon_id.
        Schema::dropIfExists('evento_salon_platillo');

        Schema::create('evento_salon_platillo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_salon_id')->constrained('evento_salon')->onDelete('cascade');
            $table->foreignId('platillo_id')->constrained('platillos')->onDelete('cascade');
            $table->integer('porciones_plan')->default(1);
            $table->integer('orden')->default(0);
            $table->string('notas')->nullable();
            $table->timestamps();

            $table->unique(['evento_salon_id', 'platillo_id'], 'evento_salon_platillo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_salon_platillo');
    }
};
