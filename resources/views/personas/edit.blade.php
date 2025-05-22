@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Persona</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('personas.update', $persona->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="cedula" class="form-label">Cédula:</label>
                <input type="text" name="cedula" 
                       class="form-control @error('cedula') is-invalid @enderror" 
                       value="{{ old('cedula', $persona->cedula) }}" 
                       required>
                @error('cedula')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nombres" class="form-label">Nombres:</label>
                <input type="text" name="nombres" 
                       class="form-control @error('nombres') is-invalid @enderror" 
                       value="{{ old('nombres', $persona->nombres) }}" 
                       required>
                @error('nombres')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>



            <div class="mb-3">
                <label for="celular" class="form-label">Celular:</label>
                <input type="text" name="celular" 
                       class="form-control @error('celular') is-invalid @enderror" 
                       value="{{ old('celular', $persona->celular) }}">
                @error('celular')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" name="correo" 
                       class="form-control @error('correo') is-invalid @enderror" 
                       value="{{ old('correo', $persona->correo) }}">
                @error('correo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="carrera_id" class="form-label">Carrera:</label>
                <select name="carrera_id" 
                        class="form-control @error('carrera_id') is-invalid @enderror" 
                        required>
                    <option value="">Seleccione una carrera</option>
                    @foreach($carreras as $carrera)
                        <option value="{{ $carrera->id_carrera }}" 
                            {{ old('carrera_id', $persona->carrera_id) == $carrera->id_carrera ? 'selected' : '' }}>
                            {{ $carrera->nombre_carrera }}
                        </option>
                    @endforeach
                </select>
                @error('carrera_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cargo_id" class="form-label">Cargo:</label>
                <select name="cargo_id" 
                        class="form-control @error('cargo_id') is-invalid @enderror" 
                        required>
                    <option value="">Seleccione un cargo</option>
                    @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id_cargo }}" 
                            {{ old('cargo_id', $persona->cargo_id) == $cargo->id_cargo ? 'selected' : '' }}>
                            {{ $cargo->nombre_cargo }}
                        </option>
                    @endforeach
                </select>
                @error('cargo_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection