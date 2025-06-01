{{-- filepath: resources/views/titulaciones/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Detalles de Titulación</h2>
    <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary mb-3">Volver</a>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-4">Tema</dt>
                <dd class="col-sm-8">{{ $titulacion->tema }}</dd>

                <dt class="col-sm-4">Estudiante</dt>
                <dd class="col-sm-8">{{ $titulacion->estudiantePersona->nombres ?? '' }}</dd>

                <dt class="col-sm-4">Cédula Estudiante</dt>
                <dd class="col-sm-8">{{ $titulacion->cedula_estudiante }}</dd>

                <dt class="col-sm-4">Director</dt>
                <dd class="col-sm-8">{{ $titulacion->directorPersona->nombres ?? '' }}</dd>

                <dt class="col-sm-4">Cédula Director</dt>
                <dd class="col-sm-8">{{ $titulacion->cedula_director }}</dd>

                <dt class="col-sm-4">Asesor 1</dt>
                <dd class="col-sm-8">{{ $titulacion->asesor1Persona->nombres ?? '' }}</dd>

                <dt class="col-sm-4">Cédula Asesor 1</dt>
                <dd class="col-sm-8">{{ $titulacion->cedula_asesor1 }}</dd>

                <dt class="col-sm-4">Periodo</dt>
                <dd class="col-sm-8">{{ $titulacion->periodo->periodo_academico ?? '' }}</dd>

                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">{{ $titulacion->estado->nombre_estado ?? '' }}</dd>

                <dt class="col-sm-4">Avance</dt>
                <dd class="col-sm-8">{{ $titulacion->avance }}%</dd>

                <dt class="col-sm-4">Observaciones</dt>
                <dd class="col-sm-8">{{ $titulacion->observaciones }}</dd>

                <dt class="col-sm-4">Resoluciones</dt>
                <dd class="col-sm-8">
                    @foreach($titulacion->resTemas as $resTema)
                        <strong>Tipo:</strong> {{ $resTema->resolucion->tipoResolucion->nombre_tipo_res ?? '' }}<br>
                        <strong>Número:</strong> {{ $resTema->resolucion->numero_res ?? '' }}<br>
                        <strong>Fecha aprobación:</strong> {{ $resTema->resolucion->fecha_res ?? '' }}<br>
                        <hr>
                    @endforeach
                </dd>
            </dl>
        </div>
    </div>
</div>
@endsection