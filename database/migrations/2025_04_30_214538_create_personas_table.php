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
            $table->string('correo')->unique()->nullable();
            
            // Claves foráneas (asumiendo que 'carreras' y 'cargos' ya existen)
            $table->unsignedBigInteger('carrera_id'); // Relación con carreras
            $table->unsignedBigInteger('cargo_id');   // Relación con cargos
            
            $table->timestamps(); // created_at y updated_at

            // Definir las relaciones a nivel de base de datos
            $table->foreign('carrera_id')->references('id_carrera')->on('carreras');
            $table->foreign('cargo_id')->references('id_cargo')->on('cargos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('personas');
    }
};