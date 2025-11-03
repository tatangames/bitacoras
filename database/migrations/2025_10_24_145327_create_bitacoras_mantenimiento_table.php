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

            $table->bigInteger('id_operador')->unsigned();
            $table->bigInteger('id_usuario')->unsigned();

            // hora y fecha del guardado
            $table->datetime('fecha_registro'); // esto cuando guarda el registro
            $table->datetime('fecha');

            $table->text('equipo')->nullable();
            $table->text('tipo_mantenimiento')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('proximo_mantenimiento')->nullable();
            $table->text('observaciones')->nullable();

            $table->foreign('id_operador')->references('id')->on('operadores');
            $table->foreign('id_usuario')->references('id')->on('operadores');
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
