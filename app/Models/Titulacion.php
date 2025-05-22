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
        'tema_id',
        'estudiante_id',
        'docente_id',
        'asesor1_id',
        'asesor2_id',
        'periodo_id',
        'estado_id',
        'avance',
        'acta_de_grado',
        'observaciones',
    ];

    public function tema()
    {
        return $this->belongsTo(Tema::class, 'tema_id', 'id_tema');
    }

    public function estudiante()
    {
        return $this->belongsTo(Persona::class, 'estudiante_id', 'id');
    }

    public function docente()
    {
        return $this->belongsTo(Persona::class, 'docente_id', 'id');
    }

    public function asesor1()
    {
        return $this->belongsTo(Persona::class, 'asesor1_id', 'id');
    }

    public function asesor2()
    {
        return $this->belongsTo(Persona::class, 'asesor2_id', 'id');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id_periodo');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoTitulacion::class, 'estado_id', 'id_estado');
    }

    public function resoluciones()
    {
        return $this->belongsToMany(Resolucion::class, 'resoluciones_titulaciones', 'titulacion_id', 'resolucion_id');
    }

    public function getRouteKeyName()
    {
        return 'id_titulacion';
    }
}
