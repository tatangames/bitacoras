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
        Schema::create('bitacoras_incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('administrador');

            // hora y fecha del guardado
            $table->datetime('fecha_registro'); // esto cuando guarda el registro
            $table->date('fecha'); // fecha que puso el usuario que se hzio la incidencia
            $table->date('fecha_solucionado')->nullable(); // fecha que fue solucionado
            $table->text('tipo_incidente')->nullable();
            $table->text('sistema_afectado')->nullable();
            $table->integer('nivel'); // ordinarios, relevantes, criticos
            $table->text('medida_correctivas')->nullable();
            $table->text('observaciones')->nullable();

            // 0: pendiente
            // 1: solucionado por usuario
            // 2: revisado por administrador
            $table->boolean('estado');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras_incidencias');
    }
};
