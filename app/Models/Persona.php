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
        'cargo_id',
    ];

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