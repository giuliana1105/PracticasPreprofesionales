<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResTema extends Model
{
    use HasFactory;

    protected $table = 'res_temas';

    protected $fillable = [
        'titulacion_id',
        'resolucion_id',
        'tema',
    ];

    public function titulacion()
    {
        return $this->belongsTo(Titulacion::class, 'titulacion_id', 'id_titulacion');
    }

    public function resolucion()
    {
        return $this->belongsTo(Resolucion::class, 'resolucion_id', 'id_Reso');
    }
}
