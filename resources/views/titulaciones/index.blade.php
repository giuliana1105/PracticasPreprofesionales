@extends('layouts.app')

@section('content')
<style>
    /* Reseteo y estilo general */
    body {
        background-color: #e9ecef;
        color: #212529;
        margin: 0;
        padding: 0 10px 20px 10px; /* algo de padding lateral pequeño para no pegarlo totalmente a borde */
        font-family: sans-serif;
        overflow-x: hidden; /* Evitar scroll horizontal por margen */
    }

    /* Contenedor usa todo el ancho posible */
    .container {
        width: 100% !important; /* fuerza ocupar todo el ancho */
        padding: 10px 0; /* menos padding para ganar espacio */
        margin: 0 auto;
        box-sizing: border-box;
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

    /* Contenedor tabla con scroll horizontal */
    .table-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow-x: auto;
        width: 100%;
    }

    /* Tabla ocupa 100% ancho */
    .table {
        width: 100% !important;
        margin-bottom: 0;
        border-collapse: collapse;
        table-layout: auto;
        min-width: 1200px; /* ancho mínimo para que no se compacte */
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
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px; /* ajustar si quieres */
    }

    /* Agrega esto para mostrar completos los campos solicitados */
    .table td[data-label="Tema"],
    .table td[data-label="Periodo"],
    .table td[data-label="Estudiante"],
    .table td[data-label="Director"],
    .table td[data-label="Asesor 1"] {
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: unset !important;
        max-width: none !important;
    }

    /* Si no usas data-label, usa nth-child según el orden de las columnas */
    .table td:nth-child(1), /* Tema */
    .table td:nth-child(2), /* Estudiante */
    .table td:nth-child(3), /* Director */
    .table td:nth-child(4), /* Asesor 1 */
    .table td:nth-child(5)  /* Periodo */ {
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: unset !important;
        max-width: none !important;
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
    }

    .btn-outline-primary:hover {
        background-color: #d32f2f;
        color: #fff;
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

    /* Responsivo */
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

    <form method="GET" action="{{ route('titulaciones.index') }}" class="mb-3 d-flex align-items-center flex-wrap">
        <label for="director_filtro" class="me-2 mb-0">Filtrar por Director:</label>
        <select name="director_filtro" id="director_filtro" class="form-control me-2 mb-2" style="width:auto;">
            <option value="">-- Todos --</option>
            @foreach($docentes as $docente)
                <option value="{{ $docente->cedula }}" {{ request('director_filtro') == $docente->cedula ? 'selected' : '' }}>
                    {{ $docente->nombres }}
                </option>
            @endforeach
        </select>

        <label for="asesor1_filtro" class="me-2 mb-0 ms-3">Filtrar por Asesor 1:</label>
        <select name="asesor1_filtro" id="asesor1_filtro" class="form-control me-2 mb-2" style="width:auto;">
            <option value="">-- Todos --</option>
            @foreach($docentes as $docente)
                <option value="{{ $docente->cedula }}" {{ request('asesor1_filtro') == $docente->cedula ? 'selected' : '' }}>
                    {{ $docente->nombres }}
                </option>
            @endforeach
        </select>

        <label for="periodo_filtro" class="me-2 mb-0 ms-3">Filtrar por Periodo:</label>
        <select name="periodo_filtro" id="periodo_filtro" class="form-control me-2 mb-2" style="width:auto;">
            <option value="">-- Todos --</option>
            @foreach($periodos as $periodo)
                <option value="{{ $periodo->id_periodo }}" {{ request('periodo_filtro') == $periodo->id_periodo ? 'selected' : '' }}>
                    {{ $periodo->periodo_academico }}
                </option>
            @endforeach
        </select>

        <label for="estado_filtro" class="me-2 mb-0 ms-3">Filtrar por Estado:</label>
        <select name="estado_filtro" id="estado_filtro" class="form-control me-2 mb-2" style="width:auto;">
            <option value="">-- Todos --</option>
            @foreach($estados as $estado)
                <option value="{{ $estado->id_estado }}" {{ request('estado_filtro') == $estado->id_estado ? 'selected' : '' }}>
                    {{ $estado->nombre_estado }}
                </option>
            @endforeach
        </select>

        <label for="fecha_inicio" class="me-2 mb-0 ms-3">Desde (Consejo directivo):</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control me-2 mb-2" style="width:auto;" value="{{ request('fecha_inicio') }}">

        <label for="fecha_fin" class="me-2 mb-0">Hasta (Consejo directivo):</label>
        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control me-2 mb-2" style="width:auto;" value="{{ request('fecha_fin') }}">

        <button type="submit" class="btn btn-primary btn-sm mb-2">Filtrar</button>
        @if(request('director_filtro') || request('asesor1_filtro') || request('periodo_filtro') || request('estado_filtro') || request('fecha_inicio') || request('fecha_fin'))
            <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary btn-sm ms-2 mb-2">Quitar filtro</a>
        @endif
    </form>

    <div class="d-flex justify-content-between mb-4 flex-wrap">
        <div>
            <a href="{{ route('titulaciones.create') }}" class="btn btn-primary mb-2">
                <i class="fas fa-plus"></i> Crear Titulación
            </a>
            <a href="{{ route('resoluciones.cambiar') }}" class="btn btn-info ms-2 mb-2">
                <i class="fas fa-exchange-alt"></i> Cambiar resoluciones
            </a>
        </div>
    </div>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tema</th>
                    <th>Estudiante</th>
                    <th>Director</th>
                    <th>Asesor 1</th>
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
                    <td>{{ $tit->estudiantePersona->nombres ?? '' }}</td>
                    <td>{{ $tit->directorPersona->nombres ?? '' }}</td>
                    <td>{{ $tit->asesor1Persona->nombres ?? '' }}</td>
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
                        {{ $fechaConsejo ?? '' }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center flex-wrap">
                            <a href="{{ route('titulaciones.show', $tit->id_titulacion) }}" class="btn btn-outline-primary btn-sm mx-1 mb-1">
                                <i class="fas fa-eye"></i> Ver detalles
                            </a>
                            <a href="{{ route('titulaciones.edit', $tit->id_titulacion) }}" 
                               class="btn btn-sm btn-warning mx-1 mb-1" 
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('titulaciones.destroy', $tit->id_titulacion) }}" 
                                  method="POST" 
                                  class="d-inline mx-1 mb-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="return confirm('¿Está seguro de eliminar esta titulación?')" 
                                        title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @if($tit->estado && strtolower($tit->estado->nombre_estado) === 'graduado')
                                @if($tit->acta_grado)
                                    <a href="{{ asset('storage/' . $tit->acta_grado) }}" target="_blank" class="btn btn-success btn-sm mx-1 mb-1">
                                        Ver acta de grado
                                    </a>
                                @else
                                    <a href="{{ route('titulaciones.edit', $tit->id_titulacion) }}#acta_grado" class="btn btn-info btn-sm mx-1 mb-1">
                                        Subir acta de grado
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
@endpush
@endsection
