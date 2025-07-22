@extends('layouts.app')

@section('content')


@php
    $user = auth()->user();
    $persona = $user ? \App\Models\Persona::where('email', $user->email)->first() : null;
    $cargo = $persona ? strtolower(trim($persona->cargo ?? '')) : '';
    $esEstudiante = $cargo === 'estudiante';
    $esDocente = $cargo === 'docente';
    $esCoordinador = $cargo === 'coordinador';
    $esDecano = $cargo === 'decano';

@endphp

@if(in_array($cargo, ['estudiante', 'docente', 'coordinador', 'decano']))
    <div class="alert alert-danger">No autorizado.</div>
    @php exit; @endphp
@endif

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
        width: 40px;
        margin-right: 15px;
        background-color: #fff;
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
        box-sizing: border-box;
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
        display: block;
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    select.form-control {
        height: auto;
        padding: 10px;
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
        margin-bottom: 0;
    }

    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid transparent;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        padding: 10px 20px;
        margin-bottom: -1px;
        cursor: pointer;
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

    .tab-pane {
        display: none;
    }
    .tab-pane.active {
        display: block;
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

    .custom-tabs {
        display: flex;
        gap: 16px;
        margin-bottom: 0;
        margin-top: 0;
    }
    .custom-tab-btn {
        padding: 16px 36px;
        border: none;
        border-radius: 8px 8px 0 0;
        background: #f4f4f4;
        color: #222;
        font-weight: 500;
        font-size: 1.1em;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        outline: none;
    }
    .custom-tab-btn.active {
        background: #219653;
        color: #fff;
        font-weight: bold;
    }
    .tab-content {
        padding: 30px 20px 20px 20px;
        background: #fff;
        border-radius: 0 0 8px 8px;
        border: 1px solid #e0e0e0;
        border-top: none;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .header-logo {
            height: 30px;
            width: 30px;
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
        <div class="header-logo"></div>
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

    <div class="form-container" style="padding-top: 0;">
        <div class="custom-tabs">
            <button type="button" class="custom-tab-btn active" id="btnManual">Ingreso Manual</button>
            <button type="button" class="custom-tab-btn" id="btnCSV">Importar desde CSV</button>
        </div>

        <div class="tab-content" id="manualTab">
            {{-- FORMULARIO MANUAL --}}
            <form action="{{ route('titulaciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- ...campos del formulario manual aquí... --}}
                <div class="form-group">
                    <label for="tema" class="form-label">Tema</label>
                    <input type="text" id="tema" name="tema" class="form-control @error('tema') is-invalid @enderror" 
                           placeholder="Tema" value="{{ old('tema') }}" required>
                    @error('tema')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Estudiante -->
                <div class="form-group">
                    <label for="estudiante_nombre">Estudiante</label>
                    <select id="estudiante_nombre" class="form-control">
                        <option value="">Seleccione...</option>
                        @foreach($personas as $persona)
                            @if(strtolower(trim($persona->cargo)) === 'estudiante')
                                <option value="{{ $persona->cedula }}" data-cedula="{{ $persona->cedula }}" {{ old('cedula_estudiante') == $persona->cedula ? 'selected' : '' }}>
                                    {{ $persona->nombres }} {{ $persona->apellidos }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <div class="cedula-mostrada mt-2">Cédula: <span id="cedula_estudiante_mostrada"></span></div>
                    <input type="hidden" name="cedula_estudiante" id="cedula_estudiante">
                </div>
                <!-- Director -->
                <div class="form-group">
                    <label for="director_nombre">Director</label>
                    <select id="director_nombre" class="form-control">
                        <option value="">Seleccione...</option>
                        @foreach($docentes as $persona)
                            <option value="{{ $persona->cedula }}" data-cedula="{{ $persona->cedula }}" {{ old('cedula_director') == $persona->cedula ? 'selected' : '' }}>
                                {{ $persona->nombres }} {{ $persona->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    <div class="cedula-mostrada mt-2">Cédula: <span id="cedula_director_mostrada"></span></div>
                    <input type="hidden" name="cedula_director" id="cedula_director">
                </div>
                <!-- Asesor 1 -->
                <div class="form-group">
                    <label for="asesor1_nombre">Asesor 1</label>
                    <select id="asesor1_nombre" class="form-control">
                        <option value="">Seleccione...</option>
                        @foreach($docentes as $persona)
                            <option value="{{ $persona->cedula }}" data-cedula="{{ $persona->cedula }}" {{ old('cedula_asesor1') == $persona->cedula ? 'selected' : '' }}>
                                {{ $persona->nombres }} {{ $persona->apellidos }}
                            </option>
                        @endforeach
                    </select>
                    <div class="cedula-mostrada mt-2">Cédula: <span id="cedula_asesor1_mostrada"></span></div>
                    <input type="hidden" name="cedula_asesor1" id="cedula_asesor1">
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
                            <li>{{ $res->numero_res }} - {{ $res->tipoResolucion->nombre_tipo_res ?? 'Tipo no especificado' }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="form-group">
                    <label for="acta_grado" class="form-label">Acta de grado (PDF)</label>
                    <input type="file" id="acta_grado" name="acta_grado" class="form-control" accept="application/pdf" disabled>
                </div>
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

        <div class="tab-content" id="csvTab" style="display:none;">
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
                <div class="form-group d-flex flex-column flex-md-row gap-2">
                    <button type="submit" class="btn btn-success me-md-2 mb-2 mb-md-0">
                        <i class="fas fa-file-import"></i> Importar
                    </button>
                    <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs personalizadas
    const btnManual = document.getElementById('btnManual');
    const btnCSV = document.getElementById('btnCSV');
    const manualTab = document.getElementById('manualTab');
    const csvTab = document.getElementById('csvTab');

    btnManual.addEventListener('click', function() {
        btnManual.classList.add('active');
        btnCSV.classList.remove('active');
        manualTab.style.display = '';
        csvTab.style.display = 'none';
    });
    btnCSV.addEventListener('click', function() {
        btnCSV.classList.add('active');
        btnManual.classList.remove('active');
        csvTab.style.display = '';
        manualTab.style.display = 'none';
    });

    // Autocompletar cédula
    function setupCedulaAutocomplete(selectId, inputCedulaId, spanCedulaId) {
        const selectElement = document.getElementById(selectId);
        const cedulaInput = document.getElementById(inputCedulaId);
        const cedulaSpan = document.getElementById(spanCedulaId);
        if (selectElement && cedulaInput && cedulaSpan) {
            function updateInput() {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    cedulaInput.value = selectedOption.getAttribute('data-cedula');
                    cedulaSpan.textContent = selectedOption.getAttribute('data-cedula');
                } else {
                    cedulaInput.value = '';
                    cedulaSpan.textContent = '';
                }
            }
            selectElement.addEventListener('change', updateInput);
            updateInput();
        }
    }
    setupCedulaAutocomplete('estudiante_nombre', 'cedula_estudiante', 'cedula_estudiante_mostrada');
    setupCedulaAutocomplete('director_nombre', 'cedula_director', 'cedula_director_mostrada');
    setupCedulaAutocomplete('asesor1_nombre', 'cedula_asesor1', 'cedula_asesor1_mostrada');

    // Habilitar campo acta de grado solo si estado es Graduado
    const estadoSelect = document.getElementById('estado_id');
    const actaInput = document.getElementById('acta_grado');
    function toggleActa() {
        const selected = estadoSelect.options[estadoSelect.selectedIndex];
        if (selected && selected.text.trim().toLowerCase() === 'graduado') {
            actaInput.disabled = false;
        } else {
            actaInput.disabled = true;
            actaInput.value = '';
        }
    }
    estadoSelect.addEventListener('change', toggleActa);
    toggleActa();
});
</script>
@endpush
@endsection