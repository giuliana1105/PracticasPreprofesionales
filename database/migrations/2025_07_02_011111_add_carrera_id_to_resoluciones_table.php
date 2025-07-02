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
        Schema::table('resoluciones', function (Blueprint $table) {
            $table->foreignId('carrera_id')->nullable()->constrained('carreras', 'id_carrera');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resoluciones', function (Blueprint $table) {
            $table->dropForeign(['carrera_id']);
            $table->dropColumn('carrera_id');
        });
    }
};
