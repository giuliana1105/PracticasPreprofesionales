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

    .module-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .module-card {
        display: flex;
        flex-direction: column;
        align-items: flex-start; /* Alinea los elementos al inicio */
        justify-content: flex-start; /* Alinea el contenido al inicio */
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: #495057;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        width: 100%; /* Asegura que la tarjeta ocupe el ancho completo de la celda */
        box-sizing: border-box; /* Incluye el padding en el ancho total */
    }

    .module-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .module-icon {
        font-size: 2.5em;
        margin-bottom: 15px;
        color: #d32f2f;
    }

    .module-title {
        font-weight: bold;
        text-align: left; /* Alinea el texto a la izquierda */
        font-size: 1.1em;
        margin-bottom: 10px; /* Añade espacio debajo del título */
    }

    /* Estilos específicos para la tabla */
    .table-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow-x: auto;  /* Agrega scroll horizontal si la tabla es muy ancha */
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
        text-align: center; /* Alinea el texto al centro por defecto */
        border-bottom: 1px solid #dee2e6;
    }

    .table td:first-child,
    .table th:first-child {
        text-align: center;
    }

    .table td:nth-child(2),
    .table th:nth-child(2) {
        text-align: left; /* Alinea la columna "NOMBRE" a la izquierda */
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }

    .btn {
        min-width: 36px;
    }

    .btn-group .btn {
        margin: 0 5px 0 0;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #d32f2f;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        border-radius: 5px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .btn-primary {
        background-color: #d32f2f;
        border-color: #d32f2f;
        border-radius: 5px;
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

    .btn-outline-info {
        color: #17a2b8;
        border-color: #17a2b8;
        border-radius: 5px;
    }

    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: #fff;
        border-color: #17a2b8;
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

    .card {
        border-radius: 5px;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.071);
    }

    .card-body {
        padding: 20px;
    }

    .py-3 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .thead-light th {
        background-color: #f8f9fa;
        color: #212529;
    }

    .d-inline {
        display: inline-flex;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .module-container {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            padding: 10px;
            gap: 10px;
        }

        .module-card {
            padding: 15px;
        }

        .module-icon {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .module-title {
            font-size: 1em;
        }

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
        <div class="header-logo">
        </div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Gestión de Tipos de Resolución</h1>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <div class="btn-group mb-2 mb-md-0">
            <a href="{{ route('tipo_resoluciones.index') }}"
               class="btn btn-outline-primary {{ !request('filter') ? 'active' : '' }}">
                Todos
            </a>
            <a href="{{ route('tipo_resoluciones.index', ['filter' => 'recientes']) }}"
               class="btn btn-outline-info {{ request('filter') == 'recientes' ? 'active' : '' }}">
                Recientes
            </a>
        </div>

        <form action="{{ route('tipo_resoluciones.index') }}" method="GET" class="d-flex flex-column flex-md-row">
            <input type="text"
                   name="search"
                   class="form-control w-100 w-md-50"
                   placeholder="Buscar por nombre"
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary ms-2">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body text-center py-3">
            <h5 class="card-title text-primary mb-1">Total de Tipos de Resolución</h5>
            <h2 class="mb-0">{{ $totalTipos ?? 0 }}</h2>
            <a href="{{ route('tipo_resoluciones.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Nuevo Tipo
            </a>
        </div>
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
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('tipo_resoluciones.edit', $tipo->id_tipo_res) }}"
                               class="btn btn-sm btn-warning mx-1"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('tipo_resoluciones.destroy', $tipo->id_tipo_res) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger mx-1"
                                        onclick="return confirm('¿Está seguro de eliminar este tipo?')"
                                        title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endpush
@endsection
