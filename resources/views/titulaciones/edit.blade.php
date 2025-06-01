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

    <div class="form-container">
        <form action="{{ route('titulaciones.update', $titulacion->id_titulacion) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="tema" class="form-label">Tema</label>
                <input type="text" id="tema" name="tema" class="form-control @error('tema') is-invalid @enderror" 
                       value="{{ $titulacion->tema }}" required>
                @error('tema')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Estudiante</label>
                <div class="person-info">
                    {{ $titulacion->estudiantePersona->nombres ?? 'No asignado' }}
                </div>
                <input type="text" name="cedula_estudiante" class="form-control @error('cedula_estudiante') is-invalid @enderror mt-2" 
                       value="{{ $titulacion->cedula_estudiante }}" required>
                @error('cedula_estudiante')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Director</label>
                <div class="person-info">
                    {{ $titulacion->directorPersona->nombres ?? 'No asignado' }}
                </div>
                <input type="text" name="cedula_director" class="form-control @error('cedula_director') is-invalid @enderror mt-2" 
                       value="{{ $titulacion->cedula_director }}" required>
                @error('cedula_director')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Asesor 1</label>
                <div class="person-info">
                    {{ $titulacion->asesor1Persona->nombres ?? 'No asignado' }}
                </div>
                <input type="text" name="cedula_asesor1" class="form-control @error('cedula_asesor1') is-invalid @enderror mt-2" 
                       value="{{ $titulacion->cedula_asesor1 }}" required>
                @error('cedula_asesor1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="periodo_id" class="form-label">Periodo</label>
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
            </div>
            
            <div class="form-group">
                <label for="estado_id" class="form-label">Estado</label>
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
            </div>
            
            <div class="form-group">
                <label for="avance" class="form-label">Avance (%)</label>
                <input type="number" id="avance" name="avance" class="form-control @error('avance') is-invalid @enderror" 
                       value="{{ $titulacion->avance }}" min="0" max="100" required>
                @error('avance')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea id="observaciones" name="observaciones" class="form-control @error('observaciones') is-invalid @enderror">{{ $titulacion->observaciones }}</textarea>
                @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
@endpush
@endsection