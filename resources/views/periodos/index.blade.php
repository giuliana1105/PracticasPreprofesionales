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
        max-width: 900px;
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
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Gestión de Períodos</h1>

    {{-- Mensaje de error personalizado --}}
    @if(session('error'))
        <div class="custom-error">
            {{ session('error') }}
            <button type="button" onclick="this.parentElement.style.display='none';">
                &times;
            </button>
        </div>
    @endif

    {{-- Mensaje de éxito personalizado --}}
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
        $esEstudiante = $persona && strtolower(trim($persona->cargo ?? '')) === 'estudiante';
    @endphp
    @if($esEstudiante)
        <div class="custom-error">No autorizado.</div>
        @php exit; @endphp
    @endif

     <div class="flex mb-4">
        <a href="{{ route('periodos.create') }}"
           class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded shadow hover:bg-red-700 transition">
            <i class="fas fa-plus mr-2"></i> Nuevo Periodo
        </a>
        <a href="{{ route('home') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded shadow hover:bg-gray-800 transition ml-2">
            <i class="fas fa-home mr-2"></i> Home
        </a>
    </div>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>PERÍODO ACADÉMICO</th>
                    <th class="text-center" width="180">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodos as $periodo)
                    <tr>
                        <td class="text-center align-middle">{{ $periodo->id_periodo }}</td>
                        <td class="align-middle">{{ $periodo->periodo_academico }}</td>
                        <td class="text-center align-middle">
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('periodos.edit', $periodo->id_periodo) }}" class="btn btn-sm btn-warning mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('periodos.destroy', $periodo->id_periodo) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mx-1" onclick="return confirm('¿Está seguro de eliminar este período?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No hay períodos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
    // Cierra automáticamente los mensajes personalizados después de 10 segundos
    setTimeout(function(){
        document.querySelectorAll('.custom-success, .custom-error').forEach(function(el){
            el.style.display = 'none';
        });
    }, 10000);
</script>
@endpush