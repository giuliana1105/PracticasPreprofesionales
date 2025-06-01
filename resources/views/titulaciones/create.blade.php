@extends('layouts.app')

@section('content')
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
        /* Idealmente, aquí se definiría un background-image si el logo es vía CSS */
        background-repeat: no-repeat;
        background-size: contain;
        height: 40px;
        width: 40px; /* Ajustado para tener un tamaño si no hay imagen */
        margin-right: 15px;
        background-color: #fff; /* Placeholder color si no hay logo */
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

    .form-container {
        background-color: #fff;
        border-radius: 5px;
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1em;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        box-sizing: border-box; /* Añadido para consistencia en el tamaño */
    }

    .form-control:focus {
        border-color: #d32f2f;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
        display: block; /* Asegura que el mensaje se muestre */
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    select.form-control {
        height: auto; /* Puede ser problemático, mejor usar padding como en inputs */
        padding: 10px; /* Asegurar padding consistente */
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 10px 20px;
        font-size: 1em;
        line-height: 1.5;
        border-radius: 5px;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
    }

    .btn-primary {
        color: #fff;
        background-color: #d32f2f;
        border-color: #d32f2f;
    }

    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .btn-success {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .resoluciones-list {
        list-style: none;
        padding: 0;
        margin: 10px 0;
    }

    .resoluciones-list li {
        padding: 8px 12px;
        background-color: #f8f9fa;
        margin-bottom: 5px;
        border-radius: 4px;
        border-left: 3px solid #d32f2f;
    }

    .section-title {
        font-size: 1.3em;
        color: #343a40;
        margin: 25px 0 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dee2e6;
    }

    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 0; /* Ajuste para que el contenido del tab se una mejor */
    }

    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid transparent;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        padding: 10px 20px;
        margin-bottom: -1px; /* Para que el borde inferior se solape con el del tab-content */
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    .nav-tabs .nav-link.active {
        color: #d32f2f;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: bold;
    }

    .tab-content {
        padding: 20px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 5px 5px;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 5px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    @media (max-width: 768px) {
        .header-logo {
            height: 30px;
            width: 30px; /* Ajustado */
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

        .form-container {
            padding: 20px;
        }

        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 0.9em;
        }
    }
</style>

<div class="container">
    <div class="header-container">
        <div class="header-logo">
            </div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Registrar Titulación</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="form-container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="csv-tab" data-bs-toggle="tab" href="#csv" role="tab" aria-controls="csv" aria-selected="false">Importar desde CSV</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="manual-tab" data-bs-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="true">Ingreso Manual</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade" id="csv" role="tabpanel" aria-labelledby="csv-tab">
                <h3 class="section-title" style="margin-top: 20px;">Importar Titulaciones desde CSV</h3>
                <div class="alert alert-info">
                    <strong>Formato requerido:</strong> El archivo debe contener las columnas: 
                    Tema, Cédula estudiante, Cédula director, Cédula asesor 1, 
                    Periodo, Estado, Avance, Observaciones
                </div>
                <form action="{{ route('titulaciones.importCsv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="csv_file" class="form-label">Archivo CSV:</label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-import"></i> Importar
                        </button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                <form action="{{ route('titulaciones.store') }}" method="POST" style="margin-top: 20px;">
                    @csrf
                    
                    <div class="form-group">
                        <label for="tema" class="form-label">Tema</label>
                        <input type="text" id="tema" name="tema" class="form-control @error('tema') is-invalid @enderror" 
                               placeholder="Tema" value="{{ old('tema') }}" required>
                        @error('tema')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
<div class="form-group">
    <label for="persona_estudiante_id" class="form-label">Estudiante</label>
    <select id="persona_estudiante_id" name="persona_estudiante_id" class="form-control" required>
        <option value="">Seleccione un estudiante</option>
        @foreach($personas as $persona)
            @if($persona->cargo && $persona->cargo->nombre_cargo == 'Estudiante')
                <option value="{{ $persona->id }}" data-cedula="{{ $persona->cedula }}">
                    {{ $persona->nombres }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="cedula_estudiante" class="form-label">Cédula Estudiante</label>
    <input type="text" id="cedula_estudiante" name="cedula_estudiante" class="form-control" readonly required>
</div>

<div class="form-group">
    <label for="persona_director_id" class="form-label">Director</label>
    <select id="persona_director_id" name="persona_director_id" class="form-control" required>
        <option value="">Seleccione un director</option>
        @foreach($personas as $persona)
            @if($persona->cargo && $persona->cargo->nombre_cargo == 'Docente')
                <option value="{{ $persona->id }}" data-cedula="{{ $persona->cedula }}">
                    {{ $persona->nombres }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="cedula_director" class="form-label">Cédula Director</label>
    <input type="text" id="cedula_director" name="cedula_director" class="form-control" readonly required>
</div>

<div class="form-group">
    <label for="persona_asesor_id" class="form-label">Asesor 1</label>
    <select id="persona_asesor_id" name="persona_asesor_id" class="form-control" required>
        <option value="">Seleccione un asesor</option>
        @foreach($personas as $persona)
            @if($persona->cargo && $persona->cargo->nombre_cargo == 'Docente')
                <option value="{{ $persona->id }}" data-cedula="{{ $persona->cedula }}">
                    {{ $persona->nombres }}
                </option>
            @endif
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="cedula_asesor1" class="form-label">Cédula Asesor 1</label>
    <input type="text" id="cedula_asesor1" name="cedula_asesor1" class="form-control" readonly required>
</div>
                    <div class="form-group">
                        <label for="periodo_id" class="form-label">Periodo</label>
                        <select id="periodo_id" name="periodo_id" class="form-control @error('periodo_id') is-invalid @enderror" required>
                            <option value="">Seleccione Periodo</option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id_periodo }}" {{ old('periodo_id') == $periodo->id_periodo ? 'selected' : '' }}>
                                    {{ $periodo->periodo_academico }}
                                </option>
                            @endforeach
                        </select>
                        @error('periodo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="estado_id" class="form-label">Estado</label>
                        <select id="estado_id" name="estado_id" class="form-control @error('estado_id') is-invalid @enderror" required>
                            <option value="">Seleccione Estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id_estado }}" {{ old('estado_id') == $estado->id_estado ? 'selected' : '' }}>
                                    {{ $estado->nombre_estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="avance" class="form-label">Avance (%)</label>
                        <input type="number" id="avance" name="avance" class="form-control @error('avance') is-invalid @enderror" 
                               placeholder="Avance (%)" min="0" max="100" value="{{ old('avance') }}" required>
                        @error('avance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones (opcional)</label>
                        <textarea id="observaciones" name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" 
                                  placeholder="Observaciones">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if(isset($resolucionesSeleccionadas) && $resolucionesSeleccionadas->count() > 0)
                    <div class="form-group">
                        <label class="form-label">Resoluciones seleccionadas:</label>
                        <ul class="resoluciones-list">
                            @foreach($resolucionesSeleccionadas as $res)
                                <li>{{ $res->numero_res }} - {{ $res->tipoResolucion->nombre ?? 'Tipo no especificado' }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Titulación
                        </button>
                        <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- CDN de Font Awesome para los iconos --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

{{-- Script para la funcionalidad de autocompletar cédula y manejo de tabs --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    function actualizarCedula(selectId, inputCedulaId) {
        const select = document.getElementById(selectId);
        const inputCedula = document.getElementById(inputCedulaId);

        function setCedula() {
            const selectedOption = select.options[select.selectedIndex];
            console.log('ID:', selectId, 'Seleccionado:', selectedOption.value, 'Cédula:', selectedOption.getAttribute('data-cedula'));
            if (selectedOption && selectedOption.value) {
                inputCedula.value = selectedOption.getAttribute('data-cedula') || '';
            } else {
                inputCedula.value = '';
            }
        }

        select.addEventListener('change', setCedula);
        setCedula();
    }

    actualizarCedula('persona_estudiante_id', 'cedula_estudiante');
    actualizarCedula('persona_director_id', 'cedula_director');
    actualizarCedula('persona_asesor_id', 'cedula_asesor1');
});
</script>
@endpush
@endsection
