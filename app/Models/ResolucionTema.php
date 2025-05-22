<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolucionTema extends Model
{
    use HasFactory;

    protected $table = 'resoluciones_temas';

    protected $fillable = [
        'resolucion_id',
        'tema_id',
    ];
}
