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
        Schema::connection('pgsql_audit')->create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID del usuario
            $table->string('action'); // Acción realizada (create, update, delete)
            $table->string('model_type'); // Modelo afectado
            $table->unsignedBigInteger('model_id'); // ID del modelo afectado
            $table->json('changes')->nullable(); // Cambios realizados
            $table->json('changes_old')->nullable(); // Cambios realizados
            $table->timestamp('performed_at'); // Fecha y hora de la acción
            $table->timestamps();

            //$table->foreign('user_id');//->references('id')->on('users')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_audit')->dropIfExists('audit_logs');
    }
};
