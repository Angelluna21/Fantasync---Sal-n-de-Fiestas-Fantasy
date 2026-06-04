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
            if (!Schema::hasColumn('evento_salon', 'adultos')) {
                $table->integer('adultos')->default(0)->after('salon_id');
            }
            if (!Schema::hasColumn('evento_salon', 'ninos')) {
                $table->integer('ninos')->default(0)->after('adultos');
            }
            if (!Schema::hasColumn('evento_salon', 'factor_nino')) {
                $table->decimal('factor_nino', 3, 2)->default(0.5)->after('ninos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not necessary for this patch
    }
};
