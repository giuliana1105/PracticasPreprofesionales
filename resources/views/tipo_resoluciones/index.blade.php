@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $persona = $user ? ($user->persona ?? null) : null;
    $cargo = session('selected_role') ? strtolower(trim(session('selected_role'))) : strtolower(trim($persona->cargo->nombre_cargo ?? $persona->cargo ?? ''));
    $bloqueados = [
        'docente',
        'decano', 'decana', 'decano/a',
        'subdecano', 'subdecana', 'subdecano/a',
        'abogado', 'abogada', 'abogado/a',
        'coordinador', 'coordinadora', 'coordinador/a'
    ];
    $esBloqueado = in_array($cargo, $bloqueados);
@endphp

@if($esBloqueado)
    <div style="text-align:center; margin-top:60px;">
        <h1 style="font-size:3em; color:#d32f2f;">403</h1>
        <div class="alert alert-danger mt-4" style="display:inline-block; font-size:1.2em;">
            El cargo {{ ucfirst($cargo) }} no tiene permisos para acceder a esta funcionalidad del sistema.
        </div>
    </div>
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
@php
    $user = auth()->user();
    $persona = $user ? ($user->persona ?? null) : null;
    $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? $persona->cargo ?? ''));
    $esDocente = $cargo === 'docente';
    $esDecano = in_array($cargo, ['decano', 'subdecano', 'subdecana', 'abogado', 'abogada']);
@endphp


<div class="container">
    <div class="header-container">
        <div class="header-logo"></div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Gestión de Tipos de Resolución</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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

    <div class="mb-4">
        <a href="{{ route('tipo_resoluciones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Tipo
        </a>
        <a href="{{ route('home') }}" class="btn btn-secondary ms-2">
            <i class="fas fa-home"></i> Home
        </a>
    </div>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>NOMBRE</th>
                    <th class="text-center">FECHA CREACIÓN</th>
                    <th class="text-center" width="150">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipos as $tipo)
                <tr>
                    <td class="text-center align-middle">{{ $tipo->id_tipo_res }}</td>
                    <td class="align-middle">{{ $tipo->nombre_tipo_res }}</td>
                    <td class="text-center align-middle">{{ $tipo->created_at->format('d/m/Y') }}</td>
                    <td class="text-center align-middle">
                        <div class="d-inline">
                            @if(!$esDocente)
                            <a href="{{ route('tipo_resoluciones.edit', $tipo->id_tipo_res) }}"
                               class="btn btn-sm btn-warning mx-1"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-danger mx-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarTipo"
                                    data-id="{{ $tipo->id_tipo_res }}"
                                    title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $tipos->withQueryString()->links() }}
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminarTipo" tabindex="-1" aria-labelledby="modalEliminarTipoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarTipoLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Está seguro de eliminar este tipo?
      </div>
      <div class="modal-footer">
        <form id="formEliminarTipo" method="POST" action="">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
    // Cierra automáticamente las alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function(){
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert){
                if(alert.classList.contains('alert-success') || alert.classList.contains('alert-danger')){
                    alert.style.display = 'none';
                }
            });
        }, 10000);

        // Script para pasar el ID al formulario del modal
        var modal = document.getElementById('modalEliminarTipo');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var tipoId = button.getAttribute('data-id');
            var form = document.getElementById('formEliminarTipo');
            form.action = '/tipo_resoluciones/' + tipoId;
        });
    });
</script>
@endpush




@php
    $user = auth()->user();
    $persona = $user ? ($user->persona ?? null) : null;
    $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? $persona->cargo ?? ''));
    $esDocente = $cargo === 'docente';
@endphp
@if($esDocente)
    <div class="alert alert-danger mt-4">No autorizado.</div>
    @php exit; @endphp
@endif
@endsection
