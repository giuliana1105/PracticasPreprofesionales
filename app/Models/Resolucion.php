<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolucion extends Model
{
    use HasFactory;

    protected $table = 'resoluciones';
    protected $primaryKey = 'id_Reso';
    protected $fillable = [
        'numero_res',
        'fecha_res',
        'tipo_res',
        'archivo_pdf',
        'seleccionada', // si la usas
        'carrera_id',   // <-- AGREGA ESTA LÍNEA
    ];

    // Relación con TipoResolucion
    public function tipoResolucion()
    {
        return $this->belongsTo(TipoResolucion::class, 'tipo_res', 'id_tipo_res');
    }

    // Relación con Temas (tabla pivote)
    public function temas()
    {
        return $this->belongsToMany(Tema::class, 'resoluciones_temas', 'resolucion_id', 'tema_id');
    }

    // Relación con Resoluciones Seleccionadas
    public function resolucionesSeleccionadas()
    {
        return $this->hasMany(ResolucionSeleccionada::class, 'resolucion_id', 'id_Reso');
    }

    // Relación con Titulaciones
    public function titulaciones()
    {
        return $this->belongsToMany(Titulacion::class, 'res_temas', 'resolucion_id', 'titulacion_id');
    }

    public function carrera()
    {
        return $this->belongsTo(\App\Models\Carrera::class, 'carrera_id', 'id_carrera');
    }
}