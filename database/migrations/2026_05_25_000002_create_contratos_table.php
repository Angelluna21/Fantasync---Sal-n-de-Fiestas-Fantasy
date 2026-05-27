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
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            
            // Flexibilidad en JSON para el formulario (fácil captura)
            $table->json('bebidas')->nullable(); // Guardará: sabor_agua, otras_bebidas, descorche
            $table->json('servicios_extras')->nullable(); // Guardará: piñata, show, mickey movil, etc.
            
            // Módulo Financiero (Cláusula DÉCIMA SEXTA)
            $table->decimal('monto_total', 10, 2)->default(0);
            $table->decimal('anticipo', 10, 2)->default(0); // El sistema validará que sean mínimo $2,500
            $table->decimal('saldo_pendiente', 10, 2)->default(0);
            
            // Módulo Legal
            $table->boolean('consentimiento_imagen')->default(true); // Cláusula 17
            $table->date('fecha_firma')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};