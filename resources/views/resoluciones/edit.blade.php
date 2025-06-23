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

    .btn-outline-pdf {
        border:1px solid #dc3545;
        border-radius:8px;
        color:#dc3545;
        background:#fff;
        padding:8px 18px;
        display:inline-flex;
        align-items:center;
        font-weight:500;
        font-size:1em;
        text-decoration:none;
        transition:box-shadow 0.2s, background 0.2s, color 0.2s;
    }
    .btn-outline-pdf:hover {
        box-shadow:0 2px 8px rgba(220,53,69,0.15);
        background:#f8d7da;
        color:#b91c1c;
        text-decoration:none;
    }
    .btn-outline-pdf i {
        margin-right:8px;
        font-size:1.3em;
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

    <h1 class="page-title">Editar Resolución</h1>

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
        <form action="{{ route('resoluciones.update', $resolucion->id_Reso) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="numero_res" class="form-label">Número de Resolución:</label>
                <input type="text" name="numero_res" id="numero_res"
                       class="form-control @error('numero_res') is-invalid @enderror"
                       value="{{ old('numero_res', $resolucion->numero_res) }}" required>
                @error('numero_res')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="fecha_res" class="form-label">Fecha:</label>
                <input type="date" name="fecha_res" id="fecha_res"
                       class="form-control @error('fecha_res') is-invalid @enderror"
                       value="{{ old('fecha_res', \Carbon\Carbon::parse($resolucion->fecha_res)->format('Y-m-d')) }}" required>
                @error('fecha_res')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tipo_res" class="form-label">Tipo de Resolución:</label>
                <select name="tipo_res" id="tipo_res"
                        class="form-control @error('tipo_res') is-invalid @enderror" required>
                    <option value="">Seleccione un tipo</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_res }}"
                            {{ old('tipo_res', $resolucion->tipo_res) == $tipo->id_tipo_res ? 'selected' : '' }}>
                            {{ $tipo->nombre_tipo_res }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_res')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Archivo Actual:</label>
                <div class="mb-2">
                    @if($resolucion->archivo_url)
                        <a href="{{ $resolucion->archivo_url }}" target="_blank"
                           style="border:1px solid #dc3545; border-radius:8px; color:#dc3545; background:#fff; padding:8px 18px; display:inline-flex; align-items:center; font-weight:500; font-size:1em; text-decoration:none; transition:box-shadow 0.2s;"
                           onmouseover="this.style.boxShadow='0 2px 8px rgba(220,53,69,0.15)'"
                           onmouseout="this.style.boxShadow='none'">
                            <i class="fas fa-file-pdf" style="margin-right:8px; font-size:1.3em;"></i> Ver PDF actual
                        </a>
                    @else
                        <span style="color:#dc3545;">No hay PDF disponible</span>
                    @endif
                </div>
                <label for="archivo_pdf" class="form-label">Nuevo Archivo PDF (opcional):</label>
                <input type="file" name="archivo_pdf" id="archivo_pdf"
                       class="form-control-file @error('archivo_pdf') is-invalid @enderror">
                @error('archivo_pdf')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('resoluciones.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Resolución
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endpush
@endsection
