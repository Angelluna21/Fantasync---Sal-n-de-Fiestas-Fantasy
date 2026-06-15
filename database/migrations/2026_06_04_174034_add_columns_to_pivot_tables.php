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
        Schema::table('platillo_ingrediente', function (Blueprint $table) {
            $table->decimal('cantidad_por_base', 8, 2)->default(1)->after('ingrediente_id');
            $table->string('nota')->nullable()->after('cantidad_por_base');
        });

        Schema::table('evento_salon_platillo', function (Blueprint $table) {
            $table->integer('porciones_plan')->default(1)->after('platillo_id');
            $table->integer('orden')->default(0)->after('porciones_plan');
            $table->string('notas')->nullable()->after('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platillo_ingrediente', function (Blueprint $table) {
            $table->dropColumn(['cantidad_por_base', 'nota']);
        });

        Schema::table('evento_salon_platillo', function (Blueprint $table) {
            $table->dropColumn(['porciones_plan', 'orden', 'notas']);
        });
    }
};
