@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Importar Personas desde CSV</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('personas.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="csv_file">Archivo CSV</label>
                    <input type="file" class="form-control-file" id="csv_file" name="csv_file" required>
                    <small class="form-text text-muted">
                        El archivo CSV debe tener las siguientes columnas: cedula, nombres,celular, correo, carrera_id, cargo_id
                    </small>
                </div>
                <button type="submit" class="btn btn-primary">Importar</button>
                <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection