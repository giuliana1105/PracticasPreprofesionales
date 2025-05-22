@extends('layouts.app') {{-- Asegúrate de que uses tu layout base --}}

@section('content')
<div class="container">
    <h1>Listado de Personas</h1>

    {{-- Mostrar mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            
            {{-- Mostrar detalles de filas omitidas --}}
            @if(session('details') && count(session('details')) > 0)
                <div class="mt-3">
                    <h5>Detalles de filas omitidas:</h5>
                    <ul class="mb-0">
                        @foreach(session('details') as $row => $reason)
                            <li>Fila {{ $row }}: {{ $reason }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('personas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Persona
        </a>
        
        
    </div>

    {{-- Tabla de personas --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Cédula</th>
                    <th>Nombres</th>
                    <th>Celular</th>
                    <th>Correo</th>
                    <th>Carrera</th>
                    <th>Cargo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personas as $persona)
                    <tr>
                        <td>{{ $persona->cedula }}</td>
                        <td>{{ $persona->nombres }}</td>
                        <td>{{ $persona->celular }}</td>
                        <td>{{ $persona->correo}}</td>
                        <td>{{ $persona->carrera->nombre_carrera ?? 'N/A' }}</td>
                        <td>{{ $persona->cargo->nombre_cargo ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('personas.destroy', $persona->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay personas registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal para importar CSV --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Importar Personas desde CSV</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('personas.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="archivo_csv">Archivo CSV</label>
                            <input type="file" class="form-control-file" id="archivo_csv" name="archivo_csv" required>
                            <small class="form-text text-muted">
                                El archivo debe tener las columnas: cedula, nombres, carrera, cargo, celular, correo
                            </small>
                        </div>
                        <div class="alert alert-info">
                            <strong>Formato requerido:</strong>
                            <ul>
                                <li>Encabezados en la primera fila</li>
                                <li>Formato CSV estándar (delimitado por comas)</li>
                                <li>Codificación UTF-8</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Importar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert ul {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Cierra automáticamente las alertas después de 5 segundos
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').alert('close');
        }, 10000);
    });
</script>
@endpush