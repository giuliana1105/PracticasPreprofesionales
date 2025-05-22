@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Cargo</h1>

    <form action="{{ route('cargos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre_cargo" class="form-label">Nombre del Cargo</label>
            <input type="text" class="form-control" id="nombre_cargo" name="nombre_cargo" required>
        </div>
        <div class="mb-3">
            <label for="siglas_cargo" class="form-label">Siglas del Cargo</label>
            <input type="text" class="form-control" id="siglas_cargo" name="siglas_cargo" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
@endsection
