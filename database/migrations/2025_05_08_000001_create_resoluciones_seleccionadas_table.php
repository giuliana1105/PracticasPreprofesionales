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
        Schema::create('resoluciones_seleccionadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resolucion_id')->constrained('resoluciones', 'id_Reso')->onDelete('cascade');
            $table->timestamp('fecha_seleccion')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resoluciones_seleccionadas');
    }
};
