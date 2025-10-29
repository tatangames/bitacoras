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
            $table->bigInteger('id_operador')->unsigned();
            $table->bigInteger('id_usuario')->unsigned();
            // hora y fecha del guardado
            $table->datetimes('fecha_registro');


            $table->datetime('fecha');

            // 0: salida 1: entrada
            $table->boolean('tipo_acceso');

            $table->text('novedad')->nullable();
            $table->text('equipo_involucrado')->nullable();
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
        Schema::dropIfExists('bitacoras_acceso');
    }
};
