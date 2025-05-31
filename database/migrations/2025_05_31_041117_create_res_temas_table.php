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
        Schema::create('res_temas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titulacion_id')->constrained('titulaciones', 'id_titulacion')->onDelete('cascade');
            $table->foreignId('resolucion_id')->constrained('resoluciones', 'id_Reso')->onDelete('cascade');
            $table->string('tema');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_temas');
    }
};
