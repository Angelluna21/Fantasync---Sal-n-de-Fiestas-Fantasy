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
            $table->boolean('es_fijo')->default(false)->after('cantidad_por_base');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platillo_ingrediente', function (Blueprint $table) {
            $table->dropColumn('es_fijo');
        });
    }
};
