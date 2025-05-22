@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Tema</h2>

    <form action="{{ route('temas.update', $tema->id_tema) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre_tema">Nombre del Tema</label>
            <input type="text" name="nombre_tema" id="nombre_tema" class="form-control @error('nombre_tema') is-invalid @enderror" value="{{ old('nombre_tema', $tema->nombre_tema) }}" required>
            @error('nombre_tema')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-4">Actualizar Tema</button>
    </form>
</div>
@endsection
