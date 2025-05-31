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
        'estudiante',
        'cedula_estudiante',
        'director',
        'cedula_director',
        'asesor1',
        'cedula_asesor1',
        'periodo_id',
        'estado_id',
        'avance',
        'observaciones'
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
}
