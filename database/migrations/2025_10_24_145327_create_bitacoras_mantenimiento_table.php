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
        Schema::create('bitacoras_mantenimiento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_usuario')->constrained('usuarios');

            // hora y fecha del guardado
            $table->datetime('fecha_registro'); // esto cuando guarda el registro
            $table->date('fecha');

            $table->text('equipo')->nullable();

            // 1- actualizacion
            // 2- preventivo
            // 3- correctivo
            $table->text('tipo_mantenimiento')->nullable();

            $table->text('descripcion')->nullable();
            $table->date('proximo_mantenimiento')->nullable();
            $table->text('observaciones')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras_mantenimiento');
    }
};
