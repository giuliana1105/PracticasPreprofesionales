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
        max-width: 700px;
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
    .card-body {
        padding: 30px 25px 25px 25px;
        background: #fff;
        border-radius: 0 0 5px 5px;
    }
    .form-group label, .form-label {
        font-weight: bold;
        color: #212529;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }
    .form-control:focus {
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
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>
    <h1 class="page-title">Crear Cargo</h1>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Por favor corrige los siguientes errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('cargos.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="nombre_cargo" class="form-label">Nombre del Cargo</label>
                    <input type="text" class="form-control" id="nombre_cargo" name="nombre_cargo" value="{{ old('nombre_cargo') }}" required>
                    @error('nombre_cargo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="siglas_cargo" class="form-label">Siglas del Cargo</label>
                    <input type="text" class="form-control" id="siglas_cargo" name="siglas_cargo" value="{{ old('siglas_cargo') }}" required>
                    @error('siglas_cargo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('cargos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').alert('close');
        }, 10000);
    });
</script>
@endpush
