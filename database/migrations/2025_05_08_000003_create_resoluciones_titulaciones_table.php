<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resoluciones_titulaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resolucion_id')->constrained('resoluciones', 'id_Reso')->onDelete('cascade');
            $table->foreignId('titulacion_id')->constrained('titulaciones', 'id_titulacion')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resoluciones_titulaciones');
    }
};