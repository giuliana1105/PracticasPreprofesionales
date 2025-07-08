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
        // Drop the unique index on numero_res (if exists)
        Schema::table('resoluciones', function (Blueprint $table) {
            // Laravel default unique index name: {table}_{column}_unique
            $table->dropUnique(['numero_res']);
            // Add composite unique index
            $table->unique(['numero_res', 'carrera_id'], 'resoluciones_numero_res_carrera_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resoluciones', function (Blueprint $table) {
            $table->dropUnique('resoluciones_numero_res_carrera_id_unique');
            // No se vuelve a crear el índice único antiguo para evitar errores con datos duplicados
        });
    }
};
