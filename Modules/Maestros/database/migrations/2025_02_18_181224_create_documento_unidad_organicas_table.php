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
        Schema::create('documentos_unidad_organica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unidad_organica_id')->constrained('unidades_organicas')->onDelete('no action');
            $table->foreignId('tipo_documento_id')->constrained('tipos_documento')->onDelete('no action');
            $table->string('tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_unidad_organica');
    }
};
