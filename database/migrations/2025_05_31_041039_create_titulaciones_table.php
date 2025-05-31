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
        Schema::create('titulaciones', function (Blueprint $table) {
            $table->id('id_titulacion');
            $table->string('tema');
            $table->string('estudiante');
            $table->string('cedula_estudiante');
            $table->string('director');
            $table->string('cedula_director');
            $table->string('asesor1');
            $table->string('cedula_asesor1');
            $table->foreignId('periodo_id')->constrained('periodos', 'id_periodo');
            $table->foreignId('estado_id')->constrained('estado_titulaciones', 'id_estado');
            $table->unsignedTinyInteger('avance');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titulaciones');
    }
};
