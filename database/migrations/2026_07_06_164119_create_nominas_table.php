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
        Schema::create('nominas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_empleado');
            $table->string('puesto');
            $table->decimal('salario_base', 10, 2);
            $table->integer('horas_extra')->default(0);
            $table->date('fecha_trabajo');
            $table->foreignId('evento_id')->constrained()->onDelete('cascade');
            $table->string('estado_pago')->default('Pendiente');
            $table->decimal('monto_total', 10, 2)->nullable();
            $table->string('metodo_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominas');
    }
};
