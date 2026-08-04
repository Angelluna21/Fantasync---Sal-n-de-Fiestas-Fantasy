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
        Schema::table('nominas', function (Blueprint $table) {
            $table->dropForeign(['evento_id']);
            $table->dropColumn(['puesto', 'salario_base', 'horas_extra', 'fecha_trabajo', 'evento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nominas', function (Blueprint $table) {
            $table->string('puesto');
            $table->decimal('salario_base', 10, 2);
            $table->integer('horas_extra')->default(0);
            $table->date('fecha_trabajo');
            $table->foreignId('evento_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
