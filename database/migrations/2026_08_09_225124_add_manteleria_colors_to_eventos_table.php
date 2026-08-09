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
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('color_cubre_mantel', 50)->nullable()->after('color_manteleria');
            $table->string('color_monos', 50)->nullable()->after('color_cubre_mantel');
            $table->string('color_camino_mesa', 50)->nullable()->after('color_monos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn(['color_cubre_mantel', 'color_monos', 'color_camino_mesa']);
        });
    }
};
