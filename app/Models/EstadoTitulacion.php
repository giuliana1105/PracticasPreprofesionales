<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoTitulacion extends Model
{
    use HasFactory;

    protected $table = 'estado_titulaciones';
    protected $primaryKey = 'id_estado';
    public $timestamps = true;

    protected $fillable = [
        'nombre_estado'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function titulaciones()
    {
        return $this->hasMany(\App\Models\Titulacion::class, 'estado_id', 'id_estado');
    }
}