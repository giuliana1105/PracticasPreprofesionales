<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposDocenteToTitulacionesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('titulaciones', function (Blueprint $table) {
            $table->text('actividades_cronograma')->nullable()->after('observaciones');
            $table->string('cumplio_cronograma')->nullable()->after('actividades_cronograma');
            $table->string('resultados')->nullable()->after('cumplio_cronograma');
            $table->integer('horas_asesoria')->nullable()->after('resultados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titulaciones', function (Blueprint $table) {
            $table->dropColumn(['actividades_cronograma', 'cumplio_cronograma', 'resultados', 'horas_asesoria']);
        });
    }
};
