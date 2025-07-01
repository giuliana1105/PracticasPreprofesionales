@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $persona = $user ? \App\Models\Persona::where('email', $user->email)->first() : null;
    $cargo = strtolower(trim($persona->cargo ?? ''));
    $esEstudiante = $persona && $cargo === 'estudiante';
    $esDocente = $persona && $cargo === 'docente';
    $esDecano = $persona && $cargo === 'decano';
    $esCoordinador = $persona && $cargo === 'coordinador';
@endphp

<style>
    body {
        background-color: #e9ecef;
        color: #212529;
        margin: 0;
        padding-bottom: 20px;
        font-family: sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .header-logo {
        background-repeat: no-repeat;
        background-size: contain;
        height: 40px;
        width: auto;
        margin-right: 15px;
    }

    .header-text-container {
        display: flex;
        flex-direction: column;
    }

    .utn-text {
        font-size: 1.2em;
        font-weight: bold;
    }

    .ibarra-text {
        font-size: 0.9em;
    }

    .page-title {
        background-color: #343a40;
        color: #fff;
        padding: 20px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 30px;
        font-size: 1.5em;
        font-weight: bold;
    }

    .table-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
        white-space: nowrap;
    }

    .table tbody tr:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
        white-space: normal;
        overflow: visible;
        text-overflow: unset;
        max-width: none;
    }

    .table td:first-child,
    .table th:first-child {
        text-align: center;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }

    .btn {
        min-width: 36px;
        border-radius: 5px;
        padding: 6px 12px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-group .btn {
        margin: 0 5px 0 0;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #d32f2f;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
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

    .btn-primary {
        background-color: #d32f2f;
        border-color: #d32f2f;
        color: white;
    }

    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
    }

    .btn-outline-primary {
        color: #d32f2f;
        border-color: #d32f2f;
        border-radius: 5px;
    }

    .btn-outline-primary:hover {
        background-color: #d32f2f;
        color: #fff;
        border-color: #d32f2f;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
        color: white;
    }

    .btn-warning {
        color: #fff;
        background-color: #f39c12;
        border-color: #f39c12;
    }

    .btn-warning:hover {
        background-color: #e08e0b;
        border-color: #e08e0b;
    }

    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    .d-flex {
        display: flex;
    }

    .flex-column {
        flex-direction: column;
    }

    .flex-md-row {
        flex-direction: row;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .mb-4 {
        margin-bottom: 20px;
    }

    .mb-2 {
        margin-bottom: 10px;
    }

    .mb-md-0 {
        margin-bottom: 0;
    }

    .ms-2 {
        margin-left: 10px;
    }

    .w-100 {
        width: 100%;
    }

    .w-md-50 {
        width: 50%;
    }

    .text-center {
        text-align: center;
    }

    .mt-4 {
        margin-top: 20px;
    }

    .align-items-center {
        align-items: center;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.071);
    }

    .card {
        border-radius: 5px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.071);
    }

    .card-body {
        padding: 20px;
    }

    .py-3 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .thead-light th {
        background-color: #f8f9fa;
        color: #212529;
    }

    .d-inline {
        display: inline-flex;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .form-label {
        font-weight: 600;
        color: #d32f2f;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .header-logo {
            height: 30px;
            margin-right: 10px;
        }

        .utn-text {
            font-size: 1em;
        }

        .ibarra-text {
            font-size: 0.8em;
        }

        .page-title {
            font-size: 1.3em;
            padding: 15px;
            margin-bottom: 20px;
        }

        .flex-md-row {
            flex-direction: column;
        }

        .w-md-50 {
            width: 100%;
        }

        .ms-2 {
            margin-left: 0;
            margin-top: 10px;
        }

        .table td, .table th {
            padding: 8px;
            font-size: 0.9em;
        }

        .btn {
            padding: 4px 8px;
            font-size: 0.9em;
        }
    }
</style>

<div class="container">
    <div class="header-container">
        <div class="header-logo"></div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Gestión de Titulaciones</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Botones de acción --}}
    <div class="d-flex flex-column flex-md-row justify-content-start mb-4" style="gap: 10px;">
        @if(!$esEstudiante && !$esDocente && !$esDecano && !$esCoordinador)
            <a href="{{ route('titulaciones.create') }}" class="btn btn-primary me-2 mb-2 mb-md-0" id="btnNuevaTitulacion">
                <i class="fas fa-plus"></i> Nueva Titulación
            </a>
            <a href="{{ route('resoluciones.cambiar') }}" class="btn btn-secondary ms-0 ms-md-2 mb-2 mb-md-0">
                <i class="fas fa-sync-alt"></i> Cambiar resoluciones seleccionadas
            </a>
        @endif
        <a href="{{ route('home') }}" class="btn btn-secondary ms-0 ms-md-2 mb-2 mb-md-0" style="margin-left:10px;">
            <i class="fas fa-home"></i> Home
        </a>
    </div>
    <div id="alertaPermiso"></div>

    {{-- Filtros --}}
    @if(!$esEstudiante)
    <div class="filter-container mb-3" style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.06); padding: 18px 18px 8px 18px;">
        <form method="GET" action="{{ route('titulaciones.index') }}">
            <div class="d-flex flex-wrap align-items-end gap-2">
                @if(!$esDocente)
                    <div class="mb-2 me-3">
                        <label for="director_filtro" class="form-label mb-1 fw-bold">Director</label>
                        <select name="director_filtro" id="director_filtro" class="form-control">
                            <option value="">-- Todos --</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->cedula }}" {{ request('director_filtro') == $docente->cedula ? 'selected' : '' }}>
                                    {{ $docente->nombres }} {{ $docente->apellidos }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2 me-3">
                        <label for="asesor1_filtro" class="form-label mb-1 fw-bold">Asesor 1</label>
                        <select name="asesor1_filtro" id="asesor1_filtro" class="form-control">
                            <option value="">-- Todos --</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->cedula }}" {{ request('asesor1_filtro') == $docente->cedula ? 'selected' : '' }}>
                                    {{ $docente->nombres }} {{ $docente->apellidos }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="mb-2 me-3">
                    <label for="periodo_filtro" class="form-label mb-1 fw-bold">Periodo</label>
                    <select name="periodo_filtro" id="periodo_filtro" class="form-control">
                        <option value="">-- Todos --</option>
                        @foreach($periodos as $periodo)
                            <option value="{{ $periodo->id_periodo }}" {{ request('periodo_filtro') == $periodo->id_periodo ? 'selected' : '' }}>
                                {{ $periodo->periodo_academico }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 me-3">
                    <label for="estado_filtro" class="form-label mb-1 fw-bold">Estado</label>
                    <select name="estado_filtro" id="estado_filtro" class="form-control">
                        <option value="">-- Todos --</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}" {{ request('estado_filtro') == $estado->id_estado ? 'selected' : '' }}>
                                {{ $estado->nombre_estado }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 me-3 d-flex align-items-end">
                    <div>
                        <label for="fecha_inicio" class="form-label mb-1 fw-bold">Desde</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                    </div>
                    <div class="ms-2">
                        <label for="fecha_fin" class="form-label mb-1 fw-bold">Hasta</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                    </div>
                </div>
                <div class="mb-2 me-3">
                    <label for="busqueda" class="form-label mb-1 fw-bold">Buscar</label>
                    <input type="text" name="busqueda" id="busqueda" class="form-control"
                        value="{{ request('busqueda') }}"
                        placeholder="Nombre del estudiante...">
                </div>
                <div class="mb-2 me-3">
                    <label for="carrera_filtro" class="form-label mb-1 fw-bold">Carrera</label>
                    <select name="carrera_filtro" id="carrera_filtro" class="form-control">
                        <option value="">-- Todas --</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->siglas_carrera }}" 
                                {{ strtolower(request('carrera_filtro')) == strtolower($carrera->siglas_carrera) ? 'selected' : '' }}>
                                {{ $carrera->siglas_carrera }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                @if(request('director_filtro') || request('asesor1_filtro') || request('periodo_filtro') || request('estado_filtro') || request('fecha_inicio') || request('fecha_fin') || request('carrera_filtro'))
                    <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary btn-sm">Quitar filtro</a>
                @endif
                <button type="submit" formaction="{{ route('titulaciones.pdf') }}" formtarget="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Tabla de resultados --}}
    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tema</th>
                    <th>Estudiante</th>
                    <th>Director</th>
                    <th>Asesor 1</th>
                    <th>Carrera</th>
                    <th>Periodo</th>
                    <th>Estado</th>
                    <th>Fecha aprobación (Consejo directivo)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($titulaciones as $tit)
                <tr>
                    <td>{{ $tit->tema }}</td>
                    <td>
                        {{ $tit->estudiantePersona->nombres ?? '' }} {{ $tit->estudiantePersona->apellidos ?? '' }}
                    </td>
                    <td>
                        {{ $tit->directorPersona->nombres ?? '' }} {{ $tit->directorPersona->apellidos ?? '' }}
                    </td>
                    <td>
                        {{ $tit->asesor1Persona->nombres ?? '' }} {{ $tit->asesor1Persona->apellidos ?? '' }}
                    </td>
                    <td>
                        {{ $tit->estudiantePersona && $tit->estudiantePersona->carrera ? $tit->estudiantePersona->carrera->siglas_carrera : '-' }}
                    </td>
                    <td>{{ $tit->periodo->periodo_academico ?? '' }}</td>
                    <td>{{ $tit->estado->nombre_estado ?? '' }}</td>
                    <td>
                        @php
                            $fechaConsejo = $tit->resTemas
                                ->filter(fn($resTema) => 
                                    isset($resTema->resolucion->tipoResolucion->nombre_tipo_res) &&
                                    strtolower($resTema->resolucion->tipoResolucion->nombre_tipo_res) === 'consejo directivo'
                                )
                                ->pluck('resolucion.fecha_res')
                                ->first();
                        @endphp
                        {{ $fechaConsejo ? \Carbon\Carbon::parse($fechaConsejo)->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center flex-wrap">
                            <a href="{{ route('titulaciones.show', $tit->id_titulacion) }}" class="btn btn-outline-primary btn-sm mx-1 mb-1" style="border: 1px solid #d32f2f;">
                                <i class="fas fa-file-pdf"></i> Ver detalles
                            </a>
                            @if($esEstudiante)
                                @if($tit->estado && strtolower($tit->estado->nombre_estado) === 'graduado' && $tit->acta_grado)
                                    <a href="{{ asset('storage/' . $tit->acta_grado) }}" target="_blank" class="btn btn-outline-primary btn-sm mx-1 mb-1" style="border: 1px solid #d32f2f;">
                                        <i class="fas fa-file-pdf"></i> Ver acta de grado
                                    </a>
                                @endif
                            @else
                                @if(!$esEstudiante && !$esDocente && !$esDecano && !$esCoordinador)
                                    <a href="{{ route('titulaciones.edit', $tit->id_titulacion) }}" class="btn btn-sm btn-warning mx-1 mb-1" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('titulaciones.destroy', $tit->id_titulacion) }}" method="POST" class="d-inline mx-1 mb-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta titulación?')" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @elseif($esDocente)
                                    <a href="{{ route('titulaciones.edit', $tit->id_titulacion) }}" class="btn btn-sm btn-warning mx-1 mb-1" title="Editar avance y observaciones">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@if($esEstudiante || $esDocente || $esDecano || $esCoordinador)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btnNuevaTitulacion');
    if(btn){
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var alerta = document.getElementById('alertaPermiso');
            alerta.innerHTML = '<div class="alert alert-danger mt-2">El usuario con el cargo {{ $esDocente ? "Docente" : ($esEstudiante ? "Estudiante" : ($esDecano ? "Decano" : "Coordinador")) }} no tiene permisos para acceder a esta funcionalidad del sistema.</div>';
        });
    }
});
</script>
@endif
@endpush
@endsection
