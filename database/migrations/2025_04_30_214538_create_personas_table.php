<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('cedula')->unique();  // Cédula única
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('celular')->nullable();
            $table->string('email')->unique()->nullable();
            
            // Claves foráneas (asumiendo que 'carreras' y 'cargos' ya existen)
            $table->unsignedBigInteger('carrera_id'); // Relación con carreras
            $table->string('cargo', 30); // Relación con cargos como string
            
            $table->timestamps(); // created_at y updated_at

            // Definir las relaciones a nivel de base de datos
            $table->foreign('carrera_id')->references('id_carrera')->on('carreras');
            // Elimina la relación con cargos
        });
    }

    public function down()
    {
        Schema::dropIfExists('personas');
    }
};