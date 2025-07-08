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
    .card {
        border-radius: 5px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.071);
        margin-bottom: 30px;
    }
    .card-header {
        background-color: #d32f2f;
        color: #fff;
        border-radius: 5px 5px 0 0;
        padding: 18px 20px;
        font-size: 1.2em;
        font-weight: bold;
    }
    .card-body {
        padding: 30px 25px 25px 25px;
        background: #fff;
        border-radius: 0 0 5px 5px;
    }
    .form-group label, .form-label {
        font-weight: bold;
        color: #212529; /* color por defecto, puedes omitir esta línea si quieres el color heredado */
    }
    .form-control, .form-select {
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }
    .form-control:focus, .form-select:focus {
        border-color: #d32f2f;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
    }
    .btn {
        min-width: 36px;
        border-radius: 5px;
        padding: 6px 12px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
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
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.95em;
    }
    .mt-4 { margin-top: 1.5rem; }
    .mb-3 { margin-bottom: 1rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    @media (max-width: 768px) {
        .container { padding: 10px; }
        .card-body { padding: 15px 8px 10px 8px; }
        .page-title { font-size: 1.1em; padding: 12px; }
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
    <h1 class="page-title">Crear Nueva Resolución</h1>
    <div class="card shadow">
        <div class="card-header">
            Formulario de Registro de Resolución
        </div>
        <div class="card-body">
            @if(session('error'))
                <div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:16px 24px;border-radius:8px;margin-bottom:20px;position:relative;">
                    {{ session('error') }}
                    <button type="button" onclick="this.parentElement.style.display='none';"
                        style="position:absolute;top:12px;right:18px;background:none;border:none;color:#721c24;font-size:18px;cursor:pointer;">&times;</button>
                </div>
            @endif
            <form action="{{ route('resoluciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="numero_res">Número de Resolución</label>
                            <input type="text" name="numero_res" id="numero_res" 
                                   class="form-control @error('numero_res') is-invalid @enderror" 
                                   value="{{ old('numero_res') }}" required>
                            @error('numero_res')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="fecha_res">Fecha</label>
                            <input type="date" name="fecha_res" id="fecha_res" 
                                   class="form-control @error('fecha_res') is-invalid @enderror" 
                                   value="{{ old('fecha_res') }}" required>
                            @error('fecha_res')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="tipo_res">Tipo de Resolución</label>
                    <select name="tipo_res" id="tipo_res" 
                            class="form-control @error('tipo_res') is-invalid @enderror" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_res }}" 
                                {{ old('tipo_res') == $tipo->id_tipo_res ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo_res }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_res')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="archivo_pdf">Archivo PDF</label>
                    <input type="file" name="archivo_pdf" id="archivo_pdf" 
                           class="form-control-file @error('archivo_pdf') is-invalid @enderror" required>
                    @error('archivo_pdf')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="carrera_id">Carrera:</label>
                    <select name="carrera_id" id="carrera_id" class="form-control" required>
                        <option value="">Seleccione una carrera</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->id_carrera }}">{{ $carrera->siglas_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('resoluciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Resolución
                    </button>
                </div>
            </form>
        </div>
    </div>


</div>
@endsection