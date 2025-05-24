@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Registrar Persona</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
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

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="manual-tab" data-bs-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="true">Ingreso Manual</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="csv-tab" data-bs-toggle="tab" href="#csv" role="tab" aria-controls="csv" aria-selected="false">Importar desde CSV</a>
            </li>
        </ul>

        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                <form action="{{ route('personas.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="cedula" class="form-label">Cédula:</label>
                        <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" 
                               value="{{ old('cedula') }}" required>
                        @error('cedula')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres:</label>
                        <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror" 
                               value="{{ old('nombres') }}" required>
                        @error('nombres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="celular" class="form-label">Celular:</label>
                        <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror" 
                               value="{{ old('celular') }}" required>
                        @error('celular')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" 
                               value="{{ old('correo') }}" required>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="carrera_id" class="form-label">Carrera:</label>
                        <select name="carrera_id" class="form-control @error('carrera_id') is-invalid @enderror" required>
                            <option value="">Seleccione una carrera</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}" 
                                    {{ old('carrera_id') == $carrera->id_carrera ? 'selected' : '' }}>
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
                        <select name="cargo_id" class="form-control @error('cargo_id') is-invalid @enderror" required>
                            <option value="">Seleccione un cargo</option>
                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo->id_cargo }}" 
                                    {{ old('cargo_id') == $cargo->id_cargo ? 'selected' : '' }}>
                                    {{ $cargo->nombre_cargo }}
                                </option>
                            @endforeach
                        </select>
                        @error('cargo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>

            <div class="tab-pane fade" id="csv" role="tabpanel" aria-labelledby="csv-tab">
                <h3>Importar personas desde un archivo CSV</h3>
                <div class="alert alert-info">
                    <strong>Formato requerido:</strong> El archivo debe contener las columnas: 
                    cedula, nombres, celular, correo, carrera, cargo
                </div>
                <form action="{{ route('personas.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="archivo_csv" class="form-label">Archivo CSV:</label>
                        <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
                    </div>
                    <button type="submit" class="btn btn-success">Importar</button>
                </form>
            </div>
        </div>
    </div>
@endsection