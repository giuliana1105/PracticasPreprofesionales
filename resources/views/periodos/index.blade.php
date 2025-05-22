@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Períodos</h1>

    {{-- Mostrar mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
        <a href="{{ route('periodos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Período
        </a>
    </div>

    {{-- Tabla de períodos --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    
                    <th>Período Académico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodos as $periodo)
                    <tr>
                        <td>{{ $periodo->id_periodo }}</td>
                        
                        <td>{{ $periodo->periodo_academico }}</td>
                        <td>
                            <a href="{{ route('periodos.edit', $periodo->id_periodo) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('periodos.destroy', $periodo->id_periodo) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center">No hay períodos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
    // Cierra automáticamente las alertas después de 10 segundos
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').alert('close');
        }, 10000);
    });
</script>
@endpush