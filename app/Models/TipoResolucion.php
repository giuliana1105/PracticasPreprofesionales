<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoResolucion extends Model
{
    use HasFactory;

    protected $table = 'tipo_resoluciones';
    protected $primaryKey = 'id_tipo_res';
    
    protected $fillable = [
        'nombre_tipo_res'
    ];
}