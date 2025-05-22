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
            $table->id('id_titulacion'); // Clave primaria

            // Claves foráneas
            $table->foreignId('tema_id')->constrained('temas', 'id_tema');
            $table->foreignId('estudiante_id')->constrained('personas', 'id');
            $table->foreignId('docente_id')->constrained('personas', 'id');
            $table->foreignId('asesor1_id')->constrained('personas', 'id');
            $table->foreignId('asesor2_id')->nullable()->constrained('personas', 'id');
            $table->foreignId('periodo_id')->constrained('periodos', 'id_periodo');
            $table->foreignId('estado_id')->constrained('estado_titulaciones', 'id_estado');

            // Campos adicionales
            $table->string('acta_de_grado')->nullable();
            $table->text('observaciones')->nullable();
            $table->integer('avance');
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
