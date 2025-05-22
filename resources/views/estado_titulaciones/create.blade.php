@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nuevo Estado de Titulación</h1>

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

    <div class="card">
        <div class="card-body">
            <form action="{{ route('estado-titulaciones.store') }}" method="POST">
                @csrf
                
                <div class="form-group row">
                    <label for="nombre_estado" class="col-sm-3 col-form-label">Nombre del Estado:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nombre_estado" name="nombre_estado" 
                               value="{{ old('nombre_estado') }}" placeholder="Ej: En proceso" required>
                        <small class="form-text text-muted">Ingrese el nombre del estado de titulación</small>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('estado-titulaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').alert('close');
        }, 10000);
    });
</script>
@endpush