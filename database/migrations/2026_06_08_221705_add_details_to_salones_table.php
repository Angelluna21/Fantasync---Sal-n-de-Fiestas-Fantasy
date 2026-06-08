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
        Schema::table('salones', function (Blueprint $table) {
            $table->integer('capacidad')->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('estado', 30)->default('activo');
            $table->text('descripcion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salones', function (Blueprint $table) {
            $table->dropColumn(['capacidad', 'direccion', 'estado', 'descripcion']);
        });
    }
};
