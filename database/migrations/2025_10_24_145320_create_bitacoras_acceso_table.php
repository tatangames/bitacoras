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
        Schema::create('bitacoras_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_operador')->constrained('operadores'); // ->cascadeOnDelete() si aplica
            $table->foreignId('id_usuario')->constrained('usuarios');  // OJO: ¿realmente debe referenciar a operadores?
            $table->foreignId('id_acceso')->constrained('tipo_acceso');
            $table->dateTime('fecha_registro'); // cuando guarda
            $table->dateTime('fecha');          // fecha del evento

            $table->text('novedad')->nullable();
            $table->text('equipo_involucrado')->nullable();
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras_acceso');
    }
};
