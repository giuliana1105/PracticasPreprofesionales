<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';  // Nombre de la tabla

    protected $primaryKey = 'id_cargo';  // Definir la clave primaria

    // Definir los campos que se pueden asignar de forma masiva
    protected $fillable = [
        'nombre_cargo',
        'siglas_cargo',
    ];
}
