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
            $table->integer('total_personas')->nullable()->after('ninos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evento_salon', function (Blueprint $table) {
            $table->dropColumn('total_personas');
        });
    }
};
