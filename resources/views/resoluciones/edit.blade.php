@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Editar Resolución</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('resoluciones.update', $resolucion->id_Reso) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero_res">Número de Resolución</label>
                            <input type="text" name="numero_res" id="numero_res" 
                                   class="form-control @error('numero_res') is-invalid @enderror" 
                                   value="{{ old('numero_res', $resolucion->numero_res) }}" required>
                            @error('numero_res')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_res">Fecha</label>
                            <input type="date" name="fecha_res" id="fecha_res" 
                                   class="form-control @error('fecha_res') is-invalid @enderror" 
                                   value="{{ old('fecha_res', \Carbon\Carbon::parse($resolucion->fecha_res)->format('Y-m-d')) }}" required>
                            @error('fecha_res')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo_res">Tipo de Resolución</label>
                    <select name="tipo_res" id="tipo_res" 
                            class="form-control @error('tipo_res') is-invalid @enderror" required>
                        <option value="">Seleccione un tipo</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_res }}" 
                                {{ old('tipo_res', $resolucion->tipo_res) == $tipo->id_tipo_res ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo_res }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_res')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Archivo Actual:</label>
                    <div class="mb-2">
                        <a href="{{ Storage::url('resoluciones/'.$resolucion->archivo_pdf) }}" 
                           target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-pdf"></i> Ver PDF actual
                        </a>
                    </div>
                    <label for="archivo_pdf">Nuevo Archivo PDF (opcional)</label>
                    <input type="file" name="archivo_pdf" id="archivo_pdf" 
                           class="form-control-file @error('archivo_pdf') is-invalid @enderror">
                    @error('archivo_pdf')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('resoluciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Resolución
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
