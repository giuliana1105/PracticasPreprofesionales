<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolucionSeleccionada extends Model
{
    use HasFactory;

    protected $table = 'resoluciones_seleccionadas'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'resolucion_id',
        'fecha_seleccion',
    ];

    // Relación con el modelo Resolucion
    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'resolucion_id', 'id_Reso');
    }
}
