<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'cedula',
        'nombres',
        'celular',
        'correo',
        'carrera_id',
        'cargo_id'
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id_carrera');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'id_cargo');
    }
}