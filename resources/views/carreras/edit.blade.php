@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Carrera</h1>

    <form action="{{ route('carreras.update', $carrera->id_carrera) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre_carrera" class="form-label">Nombre de la Carrera</label>
            <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" value="{{ $carrera->nombre_carrera }}" required>
        </div>
        <div class="mb-3">
            <label for="siglas_carrera" class="form-label">Siglas de la Carrera</label>
            <input type="text" class="form-control" id="siglas_carrera" name="siglas_carrera" value="{{ $carrera->siglas_carrera }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
