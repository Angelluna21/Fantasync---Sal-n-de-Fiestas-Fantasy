<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Paso A: primero quitamos la llave foránea (en su propia operación)
        Schema::table('platillos', function (Blueprint $table) {
            $table->dropForeign(['servicio_gastronomico_id']);
        });

        // Paso B: ahora sí, en una operación separada, borramos la columna
        Schema::table('platillos', function (Blueprint $table) {
            $table->dropColumn('servicio_gastronomico_id');
        });
    }

    public function down(): void
    {
        Schema::table('platillos', function (Blueprint $table) {
            $table->foreignId('servicio_gastronomico_id')->nullable()->constrained('servicios_gastronomicos');
        });
    }
};