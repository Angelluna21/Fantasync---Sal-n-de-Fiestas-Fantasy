<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platillos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_platillo_id')->constrained('categoria_platillos')->onDelete('cascade');
            $table->foreignId('servicio_gastronomico_id')->nullable()->constrained('servicio_gastronomicos')->onDelete('set null');
            
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            
            // El número de porciones para las que rinde esta receta base (ej. rinde para 100 personas)
            $table->integer('porciones_base')->default(100); 
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platillos');
    }
};