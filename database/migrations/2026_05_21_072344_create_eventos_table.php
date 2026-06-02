<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            
            // Logística de Fechas y Tiempos
            $table->date('fecha');
            $table->time('hora_recepcion')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->integer('horas_duracion')->default(5); // Por defecto el contrato dice 5 horas
            
            // Datos de la celebración
            $table->string('tipo_evento', 50); // Bautizo, Presentación, Cumpleaños, Comunión, Otro
            $table->string('nombre_festejado', 150)->nullable();
            $table->string('color_manteleria', 50)->nullable(); // Mantelería francesa con moño
            
            // Control interno
            $table->enum('estado', ['cotizacion', 'confirmado', 'finalizado', 'cancelado'])->default('cotizacion');
            $table->string('titulo', 150);
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['fecha', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};