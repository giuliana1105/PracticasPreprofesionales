<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'celular',
        'email',
        'carrera_id',
        'cargo', // <-- Campo string para el cargo establecido
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id_carrera');
    }

    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'carrera_persona', 'persona_id', 'carrera_id');
    }

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
}