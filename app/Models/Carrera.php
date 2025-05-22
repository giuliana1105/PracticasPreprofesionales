<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';  // Nombre de la tabla

    protected $primaryKey = 'id_carrera';  // Definir la clave primaria

    // Definir los campos que se pueden asignar de forma masiva
    protected $fillable = [
        'nombre_carrera',
        'siglas_carrera',
    ];
}
