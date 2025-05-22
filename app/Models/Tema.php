<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    protected $table = 'temas'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_tema'; // Clave primaria de la tabla

    protected $fillable = [
        'nombre_tema', // Campos permitidos para asignación masiva
    ];


public function titulaciones()
{
    return $this->hasMany(\App\Models\Titulacion::class, 'tema_id', 'id_tema');
}
    public function resoluciones()
    {
        return $this->belongsToMany(Resolucion::class, 'resoluciones_temas', 'tema_id', 'resolucion_id');
    }
}
