@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Resoluciones</h2>
        </div>
        <div class="card-body">
            <!-- Botón para crear una nueva resolución -->
            <div class="mb-3">
                <a href="{{ route('resoluciones.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Crear Nueva Resolución
                </a>
            </div>

            <!-- Formulario para seleccionar resoluciones -->
            <form action="{{ route('resoluciones.seleccionar') }}" method="POST">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Ver PDF</th> <!-- Nueva columna -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resoluciones as $resolucion)
                            <tr>
                                <td>
                                    <input type="checkbox" name="resoluciones[]" value="{{ $resolucion->id_Reso }}">
                                </td>
                                <td>{{ $resolucion->numero_res }}</td>
                                <td>{{ $resolucion->fecha_res }}</td>
                                <td>
                                    @if($resolucion->archivo_url)
                                        <a href="{{ $resolucion->archivo_url }}" target="_blank">Ver PDF</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Guardar Resoluciones Seleccionadas</button>
            </form>
           
        </div>
    </div>
</div>
@endsection
