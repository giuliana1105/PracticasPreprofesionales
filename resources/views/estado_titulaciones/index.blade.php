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

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <div class="btn-group mb-2 mb-md-0">
            <a href="{{ route('estado-titulaciones.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Estado
            </a>
            <a href="{{ route('home') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-home"></i> Home
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @php
        $user = auth()->user();
        $persona = $user ? \App\Models\Persona::where('email', $user->email)->with('cargo')->first() : null;
        $esEstudiante = $persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante';
    @endphp
    @if($esEstudiante)
        <div class="alert alert-danger">No autorizado.</div>
        @php exit; @endphp
    @endif

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
                            <form action="{{ route('estado-titulaciones.destroy', ['estado_titulacione' => $estado->id_estado]) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger mx-1"
                                        onclick="return confirm('¿Está seguro de eliminar este estado?')"
                                        title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endpush
@endsection