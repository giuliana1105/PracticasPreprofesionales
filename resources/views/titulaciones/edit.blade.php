@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $persona = $user ? \App\Models\Persona::where('email', $user->email)->first() : null;
    $esEstudiante = $persona && strtolower(trim($persona->cargo ?? '')) === 'estudiante';
    $esDocente = $persona && strtolower(trim($persona->cargo ?? '')) === 'docente';
    $esCoordinador = $persona && strtolower(trim($persona->cargo ?? '')) === 'coordinador';
    $esDecano = $persona && strtolower(trim($persona->cargo ?? '')) === 'decano';
    $cargo = strtolower(trim($persona->cargo ?? ''));
@endphp

@if(in_array($cargo, ['estudiante', 'coordinador', 'decano']))
    <div class="alert alert-danger">No autorizado.</div>
    @php exit; @endphp
@endif

@if($esDocente)
    <script>
        window.onload = function() {
            alert('Sólo puede editar el avance y las observaciones de la titulación. Los demás campos no son editables.');
        }
    </script>
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
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    select.form-control {
        height: auto;
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

    .person-info {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 5px;
        border-left: 3px solid #d32f2f;
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

        .form-container {
            padding: 20px;
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

    <h1 class="page-title">Editar Titulación</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($esDocente)
        <div class="alert alert-info">
            Sólo puede editar el <strong>avance</strong> y las <strong>observaciones</strong> de la titulación. Los demás campos no son editables.
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('titulaciones.update', $titulacion->id_titulacion) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tema --}}
            <div class="form-group">
                <label class="form-label">Tema</label>
                @if ($esDocente)
                    <input type="text" class="form-control" value="{{ $titulacion->tema }}" readonly>
                    <input type="hidden" name="tema" value="{{ $titulacion->tema }}">
                @else
                    <input type="text" id="tema" name="tema" class="form-control" value="{{ $titulacion->tema }}" required>
                @endif
            </div>

            {{-- Estudiante --}}
            <div class="form-group">
                <label class="form-label">Estudiante</label>
                @if($esDocente)
                    <input type="text" class="form-control" value="{{ $personaEstudiante->nombres ?? '' }} {{ $personaEstudiante->apellidos ?? '' }}" readonly>
                @else
                    <select id="persona_estudiante_id" name="persona_estudiante_id" class="form-control" required>
                        <option value="">Seleccione un estudiante</option>
                        @foreach($personas as $persona)
                            @if(strtolower(trim($persona->cargo)) === 'estudiante')
                                <option value="{{ $persona->id }}"
                                    data-cedula="{{ $persona->cedula }}"
                                    {{ (old('persona_estudiante_id', $personaEstudiante->id ?? '') == $persona->id) ? 'selected' : '' }}>
                                    {{ $persona->nombres }} {{ $persona->apellidos }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label">Cédula Estudiante</label>
                <input type="text" id="cedula_estudiante" name="cedula_estudiante" class="form-control" readonly>
            </div>

            {{-- Director --}}
            <div class="form-group">
                <label class="form-label">Director</label>
                @if($esDocente)
                    <input type="text" class="form-control" value="{{ $personaDirector->nombres ?? '' }} {{ $personaDirector->apellidos ?? '' }}" readonly>
                @else
                    <select id="persona_director_id" name="persona_director_id" class="form-control" required>
                        <option value="">Seleccione un director</option>
                        @foreach($personas as $persona)
                            @if(strtolower(trim($persona->cargo)) === 'docente')
                                <option value="{{ $persona->id }}"
                                    data-cedula="{{ $persona->cedula }}"
                                    {{ (old('persona_director_id', $personaDirector->id ?? '') == $persona->id) ? 'selected' : '' }}>
                                    {{ $persona->nombres }} {{ $persona->apellidos }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label">Cédula Director</label>
                <input type="text" id="cedula_director" name="cedula_director" class="form-control" readonly>
            </div>

            {{-- Asesor 1 --}}
            <div class="form-group">
                <label class="form-label">Asesor 1</label>
                @if($esDocente)
                    <input type="text" class="form-control" value="{{ $personaAsesor->nombres ?? '' }} {{ $personaAsesor->apellidos ?? '' }}" readonly>
                @else
                    <select id="persona_asesor_id" name="persona_asesor_id" class="form-control" required>
                        <option value="">Seleccione un asesor</option>
                        @foreach($personas as $persona)
                            @if(strtolower(trim($persona->cargo)) === 'docente')
                                <option value="{{ $persona->id }}"
                                    data-cedula="{{ $persona->cedula }}"
                                    {{ (old('persona_asesor_id', $personaAsesor->id ?? '') == $persona->id) ? 'selected' : '' }}>
                                    {{ $persona->nombres }} {{ $persona->apellidos }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label">Cédula Asesor 1</label>
                <input type="text" id="cedula_asesor1" name="cedula_asesor1" class="form-control" readonly>
            </div>

            {{-- Periodo --}}
            <div class="form-group">
                <label class="form-label">Periodo</label>
                @if($esDocente)
                    <input type="text" class="form-control" value="{{ optional($periodos->firstWhere('id_periodo', $titulacion->periodo_id))->periodo_academico }}" readonly>
                @else
                    <select id="periodo_id" name="periodo_id" class="form-control @error('periodo_id') is-invalid @enderror" required>
                        @foreach($periodos as $periodo)
                            <option value="{{ $periodo->id_periodo }}" @if($titulacion->periodo_id == $periodo->id_periodo) selected @endif>
                                {{ $periodo->periodo_academico }}
                            </option>
                        @endforeach
                    </select>
                    @error('periodo_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                @endif
            </div>

            {{-- Estado --}}
            <div class="form-group">
                <label class="form-label">Estado</label>
                @if($esDocente)
                    <input type="text" class="form-control" value="{{ optional($estados->firstWhere('id_estado', $titulacion->estado_id))->nombre_estado }}" readonly>
                @else
                    <select id="estado_id" name="estado_id" class="form-control @error('estado_id') is-invalid @enderror" required>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->id_estado }}" @if($titulacion->estado_id == $estado->id_estado) selected @endif>
                                {{ $estado->nombre_estado }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                @endif
            </div>

            {{-- AVANCE y OBSERVACIONES siempre editables --}}
            <div class="form-group">
                <label for="avance" class="form-label">Avance (%)</label>
                <input type="number" id="avance" name="avance" class="form-control @error('avance') is-invalid @enderror"
                       value="{{ old('avance', $titulacion->avance) }}" min="0" max="100" required>
                @error('avance')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea id="observaciones" name="observaciones" class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $titulacion->observaciones) }}</textarea>
                @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if($esDocente)
                <div class="form-group">
                    <label for="actividades_cronograma" class="form-label">Actividades según el cronograma</label>
                    <textarea id="actividades_cronograma" name="actividades_cronograma" class="form-control @error('actividades_cronograma') is-invalid @enderror">{{ old('actividades_cronograma', $titulacion->actividades_cronograma ?? '') }}</textarea>
                    @error('actividades_cronograma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cumplio_cronograma" class="form-label">Cumplió el cronograma</label>
                    <select id="cumplio_cronograma" name="cumplio_cronograma" class="form-control @error('cumplio_cronograma') is-invalid @enderror">
                        <option value="">Seleccione</option>
                        <option value="Muy Aceptable" {{ old('cumplio_cronograma', $titulacion->cumplio_cronograma ?? '') == 'Muy Aceptable' ? 'selected' : '' }}>Muy Aceptable</option>
                        <option value="Aceptable" {{ old('cumplio_cronograma', $titulacion->cumplio_cronograma ?? '') == 'Aceptable' ? 'selected' : '' }}>Aceptable</option>
                        <option value="Poco Aceptable" {{ old('cumplio_cronograma', $titulacion->cumplio_cronograma ?? '') == 'Poco Aceptable' ? 'selected' : '' }}>Poco Aceptable</option>
                    </select>
                    @error('cumplio_cronograma')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="resultados" class="form-label">Resultados</label>
                    <select id="resultados" name="resultados" class="form-control @error('resultados') is-invalid @enderror">
                        <option value="">Seleccione</option>
                        <option value="Muy Aceptable" {{ old('resultados', $titulacion->resultados ?? '') == 'Muy Aceptable' ? 'selected' : '' }}>Muy Aceptable</option>
                        <option value="Aceptable" {{ old('resultados', $titulacion->resultados ?? '') == 'Aceptable' ? 'selected' : '' }}>Aceptable</option>
                        <option value="Poco Aceptable" {{ old('resultados', $titulacion->resultados ?? '') == 'Poco Aceptable' ? 'selected' : '' }}>Poco Aceptable</option>
                    </select>
                    @error('resultados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="horas_asesoria" class="form-label">Horas de asesoría</label>
                    <input type="number" id="horas_asesoria" name="horas_asesoria" class="form-control @error('horas_asesoria') is-invalid @enderror"
                           value="{{ old('horas_asesoria', $titulacion->horas_asesoria ?? '') }}" min="0">
                    @error('horas_asesoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            {{-- Acta de grado --}}
            <div class="form-group">
                <label for="acta_grado" class="form-label">Acta de grado (PDF)</label>
                @if($titulacion->acta_grado)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $titulacion->acta_grado) }}" target="_blank" class="btn btn-success btn-sm">
                            Ver acta de grado
                        </a>
                    </div>
                @endif
                <input type="file" id="acta_grado" name="acta_grado" class="form-control" accept="application/pdf" @if($esDocente) disabled @endif>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Titulación
                </button>
                <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@if(!$esDocente)
<script>
document.addEventListener('DOMContentLoaded', function() {
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
document.addEventListener('DOMContentLoaded', function() {
    function actualizarCedula(selectId, inputCedulaId) {
        const select = document.getElementById(selectId);
        const inputCedula = document.getElementById(inputCedulaId);

        function setCedula() {
            const selectedOption = select.options[select.selectedIndex];
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
@endif
@endpush
@endsection