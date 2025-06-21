@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e9ecef;
        color: #212529;
        margin: 0;
        padding-bottom: 20px;
        font-family: sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .header-logo {
        background-repeat: no-repeat;
        background-size: contain;
        height: 40px;
        width: auto;
        margin-right: 15px;
    }

    .header-text-container {
        display: flex;
        flex-direction: column;
    }

    .utn-text {
        font-size: 1.2em;
        font-weight: bold;
    }

    .ibarra-text {
        font-size: 0.9em;
    }

    .page-title {
        background-color: #343a40;
        color: #fff;
        padding: 20px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 30px;
        font-size: 1.5em;
        font-weight: bold;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 5px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 10px 20px;
        font-size: 1em;
        line-height: 1.5;
        border-radius: 5px;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
    }

    .btn-primary {
        color: #fff;
        background-color: #d32f2f;
        border-color: #d32f2f;
    }

    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .btn-warning {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 0.875em;
        line-height: 1.5;
    }

    .table-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }

    .text-center {
        text-align: center;
    }

    .d-inline {
        display: inline-flex;
    }

    .modal-content {
        border-radius: 5px;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-title {
        color: #343a40;
    }

    .close {
        color: #6c757d;
        opacity: 1;
    }

    @media (max-width: 768px) {
        .header-logo {
            height: 30px;
            margin-right: 10px;
        }

        .utn-text {
            font-size: 1em;
        }

        .ibarra-text {
            font-size: 0.8em;
        }

        .page-title {
            font-size: 1.3em;
            padding: 15px;
            margin-bottom: 20px;
        }

        .table td, .table th {
            padding: 8px;
        }
    }
</style>

<div class="container">
    <div class="header-container">
        <div class="header-logo"></div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Listado de Personas</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            
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
        <div class="alert alert-danger">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('import_errors'))
        <div class="alert alert-danger">
            <ul>
                @foreach(session('import_errors') as $fila => $error)
                    <li>Fila {{ $fila }}: {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('duplicados') && count(session('duplicados')) > 0)
        <div class="alert alert-info">
            <strong>Registros duplicados omitidos:</strong>
            <ul>
                @foreach(session('duplicados') as $fila => $razon)
                    <li>Fila {{ $fila }}: {{ $razon }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('personas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Persona
        </a>
        <a href="{{ route('home') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-home"></i> Home
        </a>
    </div>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Celular</th>
                    <th>email</th>
                    <th>Carrera</th>
                    <th>Cargo</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($personas as $persona)
                    <tr>
                        <td>{{ $persona->cedula }}</td>
                        <td>{{ $persona->nombres }}</td>
                        <td>{{ $persona->apellidos }}</td>
                        <td>{{ $persona->celular }}</td>
                        <td>{{ $persona->email }}</td>
                        <td>{{ $persona->carrera->nombre_carrera ?? 'N/A' }}</td>
                        <td>{{ $persona->cargo->nombre_cargo ?? 'N/A' }}</td>
                        <td class="text-center">
                            <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('personas.destroy', $persona->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay personas registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal para importar CSV -->
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
                            <input type="file" class="form-control" id="archivo_csv" name="archivo_csv" required>
                            <small class="form-text text-muted">
                                El archivo debe tener las columnas: cedula, nombres, apellidos, celular, email, carrera, cargo
                            </small>
                        </div>
                        <div class="alert alert-info">
                            <strong>Formato requerido:</strong>
                            <ul class="mb-0">
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

@php
    $user = auth()->user();
    $persona = $user ? \App\Models\Persona::where('email', $user->email)->with('cargo')->first() : null;
    $esEstudiante = $persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante';
@endphp
@if($esEstudiante)
    <div class="alert alert-danger">No autorizado.</div>
    @php exit; @endphp
@endif

@endsection