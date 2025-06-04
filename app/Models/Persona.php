<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Persona extends Authenticatable
{
    protected $table = 'personas';

    protected $fillable = [
        'cedula',
        'nombres',
        'apellidos',
        'celular',
        'correo',
        'carrera_id',
        'cargo_id',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
    ];

    // Si usas otro campo para login, por ejemplo 'correo'
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id_carrera');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'id_cargo');
    }

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
}