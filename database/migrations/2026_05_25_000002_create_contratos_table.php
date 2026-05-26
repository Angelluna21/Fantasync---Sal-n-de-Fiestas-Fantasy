<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->string('correo');
            $table->string('telefono');
            $table->date('evento_fecha');
            $table->string('recepcion_hora');
            $table->string('inicio_hora');
            $table->string('tipo_evento');
            $table->string('festejado');
            $table->string('estado');
            $table->foreignId('salon_id')->constrained('salones');
            $table->json('platillos')->nullable();
            $table->json('extras')->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
