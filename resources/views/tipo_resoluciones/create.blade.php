@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center mb-0">Crear Nuevo Tipo de Resolución</h3>
            </div>
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tipo_resoluciones.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre_tipo_res" class="form-label">Nombre del Tipo</label>
                        <input type="text" name="nombre_tipo_res" id="nombre_tipo_res" 
                               class="form-control @error('nombre_tipo_res') is-invalid @enderror" 
                               value="{{ old('nombre_tipo_res') }}"
                               required autofocus>
                        @error('nombre_tipo_res')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('tipo_resoluciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Tipo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection