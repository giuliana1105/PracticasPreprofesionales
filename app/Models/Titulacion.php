<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulacion extends Model
{
    use HasFactory;

    protected $table = 'titulaciones';
    protected $primaryKey = 'id_titulacion';

    protected $fillable = [
        'tema',
        'estudiante', // <-- agrega esto
        'cedula_estudiante',
        'director',   // <-- agrega esto si existe en tu tabla
        'cedula_director',
        'asesor1',    // <-- agrega esto si existe en tu tabla
        'cedula_asesor1',
        'periodo_id',
        'estado_id',
        'avance',
        'observaciones',
        'acta_grado',
    ];

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id_periodo');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoTitulacion::class, 'estado_id', 'id_estado');
    }

    public function resTemas()
    {
        return $this->hasMany(ResTema::class, 'titulacion_id', 'id_titulacion');
    }
    public function estudiantePersona()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'cedula_estudiante', 'cedula');
    }
    public function directorPersona()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'cedula_director', 'cedula');
    }
    public function asesor1Persona()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'cedula_asesor1', 'cedula');
    }
    public function avanceHistorial()
    {
        return $this->hasMany(\App\Models\AvanceHistorial::class, 'titulacion_id');
    }
}
