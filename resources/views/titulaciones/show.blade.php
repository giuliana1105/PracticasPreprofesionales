{{-- filepath: resources/views/titulaciones/show.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fff;
        color: #212529;
        font-family: Arial, sans-serif;
        margin: 10px 20px 10px 20px;
        font-size: 13px;
    }
    .container {
        max-width: 900px;
        margin: 0 auto;
    }
    .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        border-radius: 5px;
    }
    .header-text-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .utn-text {
        font-size: 1.1em;
        font-weight: bold;
    }
    .ibarra-text {
        font-size: 0.9em;
    }
    .page-title {
        background-color: #343a40;
        color: #fff;
        padding: 14px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 18px;
        font-size: 1.2em;
        font-weight: bold;
    }
    .details-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        margin-bottom: 20px;
    }
    .details-table th, .details-table td {
        border: 1px solid #dee2e6;
        padding: 8px 6px;
        text-align: left;
        font-size: 13px;
        vertical-align: top;
        word-break: break-word;
        white-space: normal;
    }
    .details-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        width: 30%;
    }
    .details-table tr:nth-child(odd) {
        background-color: #f6f8fa;
    }
    .details-table tr:nth-child(even) {
        background-color: #fff;
    }
    .btn {
        min-width: 36px;
        border-radius: 5px;
        padding: 6px 12px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    .btn-outline-primary {
        color: #d32f2f;
        border: 1px solid #d32f2f;
        background: #fff;
    }
    .btn-outline-primary:hover {
        background-color: #d32f2f;
        color: #fff;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
        color: white;
    }
    .mb-3 { margin-bottom: 1rem; }
    .mt-2 { margin-top: 0.5rem; }
    .fw-bold { font-weight: bold; }
    .text-muted { color: #888; }
    hr { margin: 10px 0; }
</style>

<div class="container">
    <div class="header-container">
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>
    <div class="page-title">Detalles de Titulación</div>
    <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary mb-3">Volver</a>
    <table class="details-table">
        <tr>
            <th>Tema</th>
            <td>{{ $titulacion->tema }}</td>
        </tr>
        <tr>
            <th>Estudiante</th>
            <td>{{ $titulacion->estudiantePersona->nombres ?? '' }}</td>
        </tr>
        <tr>
            <th>Cédula Estudiante</th>
            <td>{{ $titulacion->cedula_estudiante }}</td>
        </tr>
        <tr>
            <th>Director</th>
            <td>{{ $titulacion->directorPersona->nombres ?? '' }}</td>
        </tr>
        <tr>
            <th>Cédula Director</th>
            <td>{{ $titulacion->cedula_director }}</td>
        </tr>
        <tr>
            <th>Asesor 1</th>
            <td>{{ $titulacion->asesor1Persona->nombres ?? '' }}</td>
        </tr>
        <tr>
            <th>Cédula Asesor 1</th>
            <td>{{ $titulacion->cedula_asesor1 }}</td>
        </tr>
        <tr>
            <th>Periodo</th>
            <td>{{ $titulacion->periodo->periodo_academico ?? '' }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{ $titulacion->estado->nombre_estado ?? '' }}</td>
        </tr>
        <tr>
            <th>Avance</th>
            <td>{{ $titulacion->avance }}%</td>
        </tr>
        <tr>
            <th>Observaciones</th>
            <td>{{ $titulacion->observaciones }}</td>
        </tr>
        <tr>
            <th>Resoluciones</th>
            <td>
                @foreach($titulacion->resTemas as $resTema)
                    <div class="mb-2">
                        <span class="fw-bold">Tipo:</span> {{ $resTema->resolucion->tipoResolucion->nombre_tipo_res ?? '' }}<br>
                        <span class="fw-bold">Número:</span> {{ $resTema->resolucion->numero_res ?? '' }}<br>
                        <span class="fw-bold">Fecha aprobación:</span> {{ $resTema->resolucion->fecha_res ?? '' }}<br>
                        @if(!empty($resTema->resolucion->archivo_pdf))
                            <a href="{{ asset('storage/' . $resTema->resolucion->archivo_pdf) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-1">
                                <i class="fas fa-file-pdf"></i> Ver PDF
                            </a>
                        @else
                            <span class="text-muted">Sin PDF</span>
                        @endif
                    </div>
                    <hr>
                @endforeach
            </td>
        </tr>
    </table>
    @if(
        $titulacion->estado &&
        strtolower($titulacion->estado->nombre_estado) === 'graduado' &&
        $titulacion->acta_grado
    )
        <div class="mt-2">
            <a href="{{ asset('storage/' . $titulacion->acta_grado) }}" target="_blank" class="btn btn-success">
                <i class="fas fa-file-pdf"></i> Ver acta de grado
            </a>
        </div>
    @endif
</div>
@endsection