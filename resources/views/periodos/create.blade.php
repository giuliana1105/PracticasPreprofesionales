@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Nuevo Período</h1>

    {{-- Mostrar mensajes de error --}}
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

    {{-- Mostrar mensajes de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('periodos.store') }}" method="POST">
                @csrf
                
                <div class="form-group row">
                    <label for="mes_ini" class="col-sm-3 col-form-label">Mes Inicio:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="mes_ini" name="mes_ini" value="{{ old('mes_ini') }}" placeholder="Ej: Enero">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="mes_fin" class="col-sm-3 col-form-label">Mes Fin:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="mes_fin" name="mes_fin" value="{{ old('mes_fin') }}" placeholder="Ej: Marzo">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="año_ini" class="col-sm-3 col-form-label">Año Inicio:</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="año_ini" name="año_ini" value="{{ old('año_ini') }}" placeholder="Ej: 2023">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="año_fin" class="col-sm-3 col-form-label">Año Fin:</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="año_fin" name="año_fin" value="{{ old('año_fin') }}" placeholder="Ej: 2023">
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('periodos.index') }}" class="btn btn-secondary">
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