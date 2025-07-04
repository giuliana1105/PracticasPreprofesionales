@extends('layouts.app')

@section('content')


@php
    $user = auth()->user();
    $cargo = strtolower(trim($user->cargo ?? ''));
    $esSecretaria = in_array($cargo, ['secretario', 'secretaria']);
    $esEstudiante = $cargo === 'estudiante';
@endphp
@if($esEstudiante)
    <div class="alert alert-danger">No autorizado.</div>
    @php exit; @endphp
@endif

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

    .btn-info {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
        color: #fff;
    }

    .mt-2 {
        margin-top: 0.5rem !important;
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

    {{-- Filtros de Todos y Recientes + Buscador --}}
    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px; margin-bottom: 24px;">
        {{-- Tabs de Todos y Recientes --}}
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('personas.index', ['filtro' => 'todos']) }}"
               class="px-4 py-2 {{ request('filtro', 'todos') == 'todos' ? 'bg-red-600 text-white' : 'bg-white text-red-600 border border-red-600' }} text-sm font-semibold rounded shadow transition"
               style="text-decoration:none;">
                Todos
            </a>
            <a href="{{ route('personas.index', ['filtro' => 'recientes']) }}"
               class="px-4 py-2 {{ request('filtro') == 'recientes' ? 'bg-red-600 text-white' : 'bg-white text-red-600 border border-red-600' }} text-sm font-semibold rounded shadow transition"
               style="text-decoration:none;">
                Recientes
            </a>
        </div>
        {{-- Buscador --}}
        <form action="{{ route('personas.index') }}" method="GET" style="flex:1; min-width:220px; display:flex; align-items:center; gap:8px;">
            <input type="hidden" name="filtro" value="{{ request('filtro', 'todos') }}">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por cédula, nombre o apellido"
                   style="flex:1; padding:8px 12px; border:1px solid #d32f2f; border-radius:5px; font-size:1em;">
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded shadow hover:bg-red-700 transition">
                <i class="fas fa-search mr-2"></i> Buscar
            </button>
        </form>
    </div>

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
                    <th>Email</th>
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
                        <td>
                            @if($persona->carreras && $persona->carreras->count())
                                @foreach($persona->carreras as $carrera)
                                    <div>{{ $carrera->siglas_carrera }}</div>
                                @endforeach
                            @else
                                {{ $persona->carrera->siglas_carrera ?? 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @php
                                $cargos = [
                                    'secretario_general' => 'Secretario General',
                                    'secretario' => 'Secretario/a',
                                    'abogado' => 'Abogado/a',
                                    'decano' => 'Decano/a',
                                    'subdecano' => 'Subdecano/a',
                                    'docente' => 'Docente',
                                    'estudiante' => 'Estudiante',
                                    'coordinador' => 'Coordinador/a',
                                ];
                            @endphp
                            {{ $cargos[$persona->cargo] ?? ucfirst($persona->cargo) }}
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex flex-column align-items-center">
                                <div>
                                    @php
                                        // Solo puede editar/eliminar/resetear si:
                                        // - Es secretaria/o
                                        // - El registro es estudiante
                                        // - El estudiante pertenece a una de sus carreras asignadas
                                        $puedeEditar = true;
                                        if ($esSecretaria) {
                                            $puedeEditar = (
                                                $persona->cargo === 'estudiante'
                                                && isset($carrerasAsignadas)
                                                && $persona->carreras->pluck('id_carrera')->intersect($carrerasAsignadas)->count() > 0
                                            );
                                        }
                                    @endphp

                                    @if($puedeEditar)
                                        <a href="{{ route('personas.edit', $persona->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fas fa-edit text-white"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarPersona"
                                            data-id="{{ $persona->id }}"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                                @php
                                    $esAdmin = $user && in_array(strtolower($user->cargo), ['secretario', 'secretario_general']);
                                    // Solo puede resetear si puede editar (es secretaria/o y estudiante de su carrera)
                                @endphp
                                @if($esAdmin && $puedeEditar)
                                    <form action="{{ route('personas.reset-password', $persona->id) }}" method="POST" class="d-inline mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm"
                                            style="background-color: #17a2b8; border-color: #17a2b8; color: #fff;"
                                            onclick="return confirm('¿Está seguro de resetear la contraseña de {{ $persona->nombres }}?')">
                                            <i class="fas fa-key"></i> Resetear contraseña
                                        </button>
                                    </form>
                                @endif
                            </div>
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
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminarPersona" tabindex="-1" aria-labelledby="modalEliminarPersonaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarPersonaLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Está seguro de eliminar esta persona?
      </div>
      <div class="modal-footer">
        <form id="formEliminarPersona" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
    // Script para pasar el ID al formulario del modal
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modalEliminarPersona');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var personaId = button.getAttribute('data-id');
            var form = document.getElementById('formEliminarPersona');
            form.action = '/personas/' + personaId;
        });
    });
</script>
@endpush

@endsection