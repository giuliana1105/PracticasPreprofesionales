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
        align-items: flex-start;
        justify-content: flex-start;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: #495057;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        width: 100%;
        box-sizing: border-box;
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
        text-align: left;
        font-size: 1.1em;
        margin-bottom: 10px;
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
    .table th:nth-child(2),
    .table td:nth-child(3),
    .table th:nth-child(3),
    .table td:nth-child(4),
    .table th:nth-child(4) {
        text-align: left;
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

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 5px;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #218838;
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

    .mb-3 {
        margin-bottom: 15px;
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

    .card-header {
        background-color: #d32f2f;
        color: white;
        padding: 15px 20px;
        border-radius: 5px 5px 0 0 !important;
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

@php
    $user = auth()->user();
    $persona = $user ? \App\Models\Persona::where('email', $user->email)->first() : null;
    $esEstudiante = $persona && strtolower(trim($persona->cargo ?? '')) === 'estudiante';
@endphp
@if($esEstudiante)
    <div class="alert alert-danger">No autorizado.</div>
    @php exit; @endphp
@endif

<div class="container">
    <div class="header-container">
        <div class="header-logo">
        </div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Gestión de Resoluciones</h1>

    <div class="flex mb-4">
        <a href="{{ route('resoluciones.create') }}"
           class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded shadow hover:bg-red-700 transition">
            <i class="fas fa-plus mr-2"></i> Crear Nueva Resolución
        </a>
        <a href="{{ route('home') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded shadow hover:bg-gray-800 transition ml-2">
            <i class="fas fa-home mr-2"></i> Home
        </a>
    </div>

    {{-- Filtros de Todos y Recientes + Buscador --}}
    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px; margin-bottom: 24px;">
        {{-- Tabs de Todos y Recientes --}}
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('resoluciones.index', ['filtro' => 'todos']) }}"
               class="px-4 py-2 {{ request('filtro', 'todos') == 'todos' ? 'bg-red-600 text-white' : 'bg-white text-red-600 border border-red-600' }} text-sm font-semibold rounded shadow transition"
               style="text-decoration:none;">
                Todos
            </a>
            <a href="{{ route('resoluciones.index', ['filtro' => 'recientes']) }}"
               class="px-4 py-2 {{ request('filtro') == 'recientes' ? 'bg-red-600 text-white' : 'bg-white text-red-600 border border-red-600' }} text-sm font-semibold rounded shadow transition"
               style="text-decoration:none;">
                Recientes
            </a>
        </div>
        {{-- Buscador --}}
        <form action="{{ route('resoluciones.index') }}" method="GET" style="flex:1; min-width:220px; display:flex; align-items:center; gap:8px;">
            <input type="hidden" name="filtro" value="{{ request('filtro', 'todos') }}">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por número o tipo"
                   style="flex:1; padding:8px 12px; border:1px solid #d32f2f; border-radius:5px; font-size:1em;">
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded shadow hover:bg-red-700 transition">
                <i class="fas fa-search mr-2"></i> Buscar
            </button>
        </form>
    </div>

    {{-- Mensajes de éxito y error personalizados --}}
    @if(session('success'))
        <div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:16px 24px;border-radius:8px;margin-bottom:20px;position:relative;">
            {{ session('success') }}
            <button type="button" onclick="this.parentElement.style.display='none';"
                style="position:absolute;top:12px;right:18px;background:none;border:none;color:#155724;font-size:18px;cursor:pointer;">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:16px 24px;border-radius:8px;margin-bottom:20px;position:relative;">
            {{ session('error') }}
            <button type="button" onclick="this.parentElement.style.display='none';"
                style="position:absolute;top:12px;right:18px;background:none;border:none;color:#721c24;font-size:18px;cursor:pointer;">&times;</button>
        </div>
    @endif

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">Seleccionar</th>
                    <th>Número</th>
                    <th>Fecha aprobación</th>
                    <th>Tipo de Resolución</th>
                    <th>Ver PDF</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resoluciones as $resolucion)
                    <tr>
                        <td class="text-center align-middle">
                            <input type="checkbox" name="resoluciones[]" value="{{ $resolucion->id_Reso }}" form="form-seleccionar">
                        </td>
                        <td class="align-middle">{{ $resolucion->numero_res }}</td>
                        <td class="align-middle">{{ $resolucion->fecha_res }}</td>
                        <td class="align-middle">{{ $resolucion->tipoResolucion->nombre_tipo_res ?? '-' }}</td>
                        <td class="align-middle">
                            @if($resolucion->archivo_url)
                                <a href="{{ $resolucion->archivo_url }}" target="_blank"
                                   style="border:1px solid #dc3545; border-radius:8px; color:#dc3545; background:#fff; padding:8px 18px; display:inline-flex; align-items:center; font-weight:500; font-size:1em; text-decoration:none; transition:box-shadow 0.2s;"
                                   onmouseover="this.style.boxShadow='0 2px 8px rgba(220,53,69,0.15)'"
                                   onmouseout="this.style.boxShadow='none'">
                                    <i class="fas fa-file-pdf" style="margin-right:8px; font-size:1.3em;"></i> Ver PDF
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="align-middle">
                            {{-- Botón editar --}}
                            <a href="{{ route('resoluciones.edit', $resolucion->id_Reso) }}"
                               class="btn btn-warning btn-sm"
                               title="Editar"
                               style="background:#f39c12;border:none;color:#fff;width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;font-size:1.3em;border-radius:8px;margin-right:4px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            {{-- Botón eliminar --}}
                            <button type="button"
                                    class="btn btn-danger btn-sm"
                                    style="background:#dc3545;border:none;color:#fff;width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;font-size:1.3em;border-radius:8px;"
                                    onclick="confirmarEliminar('{{ $resolucion->id_Reso }}')"
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                            {{-- Formulario eliminar oculto --}}
                            <form id="form-eliminar-{{ $resolucion->id_Reso }}" action="{{ route('resoluciones.destroy', $resolucion->id_Reso) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Formulario separado solo para enviar las resoluciones seleccionadas --}}
        <div style="display: flex; justify-content: center; width: 100%; margin-top: 1.5rem;">
            <form id="form-seleccionar" action="{{ route('resoluciones.seleccionar') }}" method="POST">
                @csrf
                <button type="submit"
                    style="display:inline-flex;align-items:center;padding:8px 16px;background:#22c55e;color:#fff;font-size:0.95rem;font-weight:600;border-radius:6px;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:none;transition:background 0.2s;gap:8px;"
                    onmouseover="this.style.background='#16a34a';"
                    onmouseout="this.style.background='#22c55e';">
                    <i class="fas fa-save" style="margin-right:8px;"></i> Guardar Resoluciones Seleccionadas
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
    function confirmarEliminar(id) {
        if (confirm('¿Está seguro que desea eliminar esta resolución?')) {
            document.getElementById('form-eliminar-' + id).submit();
        }
    }
</script>
@endpush
@endsection