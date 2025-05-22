<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    protected $table = 'periodos';
    protected $primaryKey = 'id_periodo';

    protected $fillable = [
        'mes_ini',
        'mes_fin',
        'año_ini',
        'año_fin',
        'periodo_academico',
    ];
}
