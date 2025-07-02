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
        text-align: center;
        border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
    }

    .table td:first-child,
    .table th:first-child {
        text-align: center;
    }

    .table td:nth-child(2),
    .table th:nth-child(2) {
        text-align: left;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }

    .btn {
        min-width: 36px;
        border-radius: 5px;
    }

    .btn-group .btn {
        margin: 0 5px 0 0;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .btn-primary {
        background-color: #d32f2f;
        border-color: #d32f2f;
    }

    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    .btn-outline-primary {
        color: #d32f2f;
        border-color: #d32f2f;
        border-radius: 5px;
    }

    .btn-outline-primary:hover {
        background-color: #d32f2f;
        color: #fff;
        border-color: #d32f2f;
    }

    .btn-warning {
        color: #fff;
        background-color: #f39c12;
        border-color: #f39c12;
        border-radius: 5px;
    }

    .btn-warning:hover {
        background-color: #e08e0b;
        border-color: #e08e0b;
    }

    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        border-radius: 5px;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    .d-flex {
        display: flex;
    }

    .flex-column {
        flex-direction: column;
    }

    .flex-md-row {
        flex-direction: row;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .mb-4 {
        margin-bottom: 20px;
    }

    .mb-2 {
        margin-bottom: 10px;
    }

    .mb-md-0 {
        margin-bottom: 0;
    }

    .ms-2 {
        margin-left: 10px;
    }

    .w-100 {
        width: 100%;
    }

    .w-md-50 {
        width: 50%;
    }

    .text-center {
        text-align: center;
    }

    .mt-4 {
        margin-top: 20px;
    }

    .align-items-center {
        align-items: center;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.071);
    }

    /* Mensaje de éxito personalizado */
    .custom-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 16px 24px;
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
        text-align: left;
    }
    .custom-success button {
        position: absolute;
        top: 12px;
        right: 18px;
        background: none;
        border: none;
        color: #155724;
        font-size: 18px;
        cursor: pointer;
    }
    /* Mensaje de error personalizado */
    .custom-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 16px 24px;
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
        text-align: left;
    }
    .custom-error button {
        position: absolute;
        top: 12px;
        right: 18px;
        background: none;
        border: none;
        color: #721c24;
        font-size: 18px;
        cursor: pointer;
    }

    .btn-red {
        background-color: #d32f2f;
        color: #fff;
        border: none;
        font-weight: 600;
        padding: 8px 22px;
        border-radius: 8px;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        font-size: 1rem;
    }
    .btn-red:hover {
        background-color: #b71c1c;
        color: #fff;
    }
    .btn-gray {
        background-color: #6c757d;
        color: #fff;
        border: none;
        font-weight: 600;
        padding: 8px 22px;
        border-radius: 8px;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        font-size: 1rem;
        margin-left: 8px;
    }
    .btn-gray:hover {
        background-color: #495057;
        color: #fff;
    }
    .btn-red i, .btn-gray i {
        margin-right: 8px;
        font-size: 1.1em;
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

        .flex-md-row {
            flex-direction: column;
        }

        .w-md-50 {
            width: 100%;
        }

        .ms-2 {
            margin-left: 0;
            margin-top: 10px;
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

    <h1 class="page-title">Gestión de Estados de Titulación</h1>

    @if(session('error'))
        <div class="custom-error">
            {{ session('error') }}
            <button type="button" onclick="this.parentElement.style.display='none';">
                &times;
            </button>
        </div>
    @endif
    @if(session('success'))
        <div class="custom-success">
            {{ session('success') }}
            <button type="button" onclick="this.parentElement.style.display='none';">
                &times;
            </button>
        </div>
    @endif

    @php
        $user = auth()->user();
        $persona = $user ? \App\Models\Persona::where('email', $user->email)->first() : null;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        $esDecano = in_array($cargo, ['decano', 'subdecano', 'subdecana', 'abogado', 'abogada']);
        $esEstudiante = $persona && $cargo === 'estudiante';
    @endphp
    @if($esEstudiante)
        <div class="custom-error">No autorizado.</div>
        @php exit; @endphp
    @endif

    {{-- Botones de acción --}}
    <div class="d-flex mb-4">
        <a href="{{ route('estado-titulaciones.create') }}" class="btn-red">
            <i class="fas fa-plus"></i> Crear Estado
        </a>
        <a href="{{ route('home') }}" class="btn-gray">
            <i class="fas fa-home"></i> Home
        </a>
    </div>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>NOMBRE DEL ESTADO</th>
                    <th class="text-center" width="150">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estados as $estado)
                <tr>
                    <td class="text-center align-middle">{{ $estado->id_estado }}</td>
                    <td class="align-middle">{{ $estado->nombre_estado }}</td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('estado-titulaciones.edit', ['estado_titulacione' => $estado->id_estado]) }}"
                               class="btn btn-sm btn-warning mx-1"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-danger mx-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarEstado"
                                    data-id="{{ $estado->id_estado }}"
                                    title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">No hay estados de titulación registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modalEliminarEstado" tabindex="-1" aria-labelledby="modalEliminarEstadoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarEstadoLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Está seguro de eliminar este estado?
      </div>
      <div class="modal-footer">
        <form id="formEliminarEstado" method="POST" action="">
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
    // Cierra automáticamente los mensajes personalizados después de 10 segundos
    setTimeout(function(){
        document.querySelectorAll('.custom-success, .custom-error').forEach(function(el){
            el.style.display = 'none';
        });
    }, 10000);

    // Script para pasar el ID al formulario del modal
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('modalEliminarEstado');
        modal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var estadoId = button.getAttribute('data-id');
            var form = document.getElementById('formEliminarEstado');
            form.action = '/estado-titulaciones/' + estadoId;
        });
    });
</script>
@endpush
@endsection