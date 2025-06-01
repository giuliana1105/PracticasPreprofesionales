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
      // Nueva migración
Schema::table('titulaciones', function (Blueprint $table) {
    $table->string('acta_grado')->nullable()->after('observaciones');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titulaciones', function (Blueprint $table) {
            //
        });
    }
};
