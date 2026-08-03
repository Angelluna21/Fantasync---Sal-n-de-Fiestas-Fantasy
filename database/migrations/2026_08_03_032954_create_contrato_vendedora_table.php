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
        Schema::create('contrato_vendedora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendedora_id')->constrained('vendedoras')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato_vendedora');
    }
};
