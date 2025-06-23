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
        Schema::create('resoluciones', function (Blueprint $table) {
            $table->id('id_Reso');
            $table->string('numero_res')->unique();
            $table->date('fecha_res');
            $table->foreignId('tipo_res')->constrained('tipo_resoluciones', 'id_tipo_res');
            $table->string('archivo_pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resoluciones');
    }
};
