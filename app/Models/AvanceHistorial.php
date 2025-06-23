<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvanceHistorial extends Model
{
    protected $fillable = [
        'titulacion_id', 'docente_id', 'campo', 'valor_anterior', 'valor_nuevo'
    ];

    public function docente()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'docente_id');
    }
}
