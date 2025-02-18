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
        Schema::create('unidades_organicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_unidad_organica_id')->constrained('tipos_unidad_organica')->onDelete('cascade');
            $table->foreignId('padre_id')->nullable()->constrained('unidades_organicas')->onDelete('cascade');
            $table->foreignId('tipo_derivacion_id')->constrained('tipos_derivacion')->onDelete('cascade');
            $table->string('nombre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_organicas');
    }
};
