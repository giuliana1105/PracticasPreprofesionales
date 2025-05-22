@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Titulaciones</h1>

    <!-- Botones para crear nueva titulación y cambiar resoluciones -->
    <div class="mb-3">
        <a href="{{ route('titulaciones.create') }}" class="btn btn-primary">Crear nueva titulación</a>
        <form action="{{ route('resoluciones.cambiar') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-secondary">Cambiar de resoluciones</button>
        </form>
    </div>

    <table class="table mt-4 table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tema</th>
                <th>Estudiante</th>
                <th>Docente</th>
                <th>Asesor 1</th>
                <th>Asesor 2</th>
                <th>Periodo</th>
                <th>Estado</th>
                <th>Acta de Grado</th>
                <th>Avance</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($titulaciones as $titulacion)
                <tr>
                    <td>{{ $titulacion->id_titulacion }}</td>
                    <td>{{ $titulacion->tema->nombre_tema ?? '' }}</td>
                    <td>{{ $titulacion->estudiante->nombres ?? '' }}</td>
                    <td>{{ $titulacion->docente->nombres ?? '' }}</td>
                    <td>{{ $titulacion->asesor1->nombres ?? '' }}</td>
                    <td>{{ $titulacion->asesor2->nombres ?? '' }}</td>
                    <td>{{ $titulacion->periodo->periodo_academico ?? '' }}</td>
                    <td>{{ $titulacion->estado->nombre_estado ?? '' }}</td>
                    <td>
                        @if($titulacion->acta_de_grado)
                            <a href="{{ asset('storage/' . $titulacion->acta_de_grado) }}" target="_blank">Ver PDF</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $titulacion->avance }}%</td>
                    <td>
                        <a href="{{ route('titulaciones.edit', $titulacion->id_titulacion) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('titulaciones.destroy', $titulacion->id_titulacion) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
