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
        Schema::table('evento_salon', function (Blueprint $table) {
            $table->integer('adultos')->default(0)->after('salon_id');
            $table->integer('ninos')->default(0)->after('adultos');
            $table->decimal('factor_nino', 3, 2)->default(0.5)->after('ninos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evento_salon', function (Blueprint $table) {
            $table->dropColumn([
                'adultos',
                'ninos',
                'factor_nino',
            ]);
        });
    }
};
