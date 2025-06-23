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
        Schema::create('avance_historials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('titulacion_id');
            $table->unsignedBigInteger('docente_id');
            $table->string('campo'); // 'avance' o 'observaciones'
            $table->text('valor_anterior')->nullable();
            $table->text('valor_nuevo')->nullable();
            $table->timestamps();

            $table->foreign('titulacion_id')->references('id_titulacion')->on('titulaciones')->onDelete('cascade');
            $table->foreign('docente_id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avance_historials');
    }
};
