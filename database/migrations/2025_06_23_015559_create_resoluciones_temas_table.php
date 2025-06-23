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
        Schema::create('resoluciones_temas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resolucion_id');
            $table->unsignedBigInteger('tema_id');
            $table->timestamps();

            $table->foreign('resolucion_id')->references('id_Reso')->on('resoluciones')->onDelete('cascade');
            $table->foreign('tema_id')->references('id_tema')->on('temas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resoluciones_temas');
    }
};
