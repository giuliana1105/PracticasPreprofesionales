{{-- filepath: resources/views/titulaciones/index.blade.php --}}
@extends('layouts.app')

@section('content')
<h2>Listado de Titulaciones</h2>
<a href="{{ route('titulaciones.create') }}">Crear Titulación</a>
@if(session('success'))
    <div style="color: green; font-weight: bold;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="color: red; font-weight: bold;">
        {{ session('error') }}
    </div>
@endif
<table border="1">
    <thead>
        <tr>
            <th>Tema</th>
            <th>Estudiante</th>
            <th>Cédula Estudiante</th>
            <th>Director</th>
            <th>Cédula Director</th>
            <th>Asesor 1</th>
            <th>Cédula Asesor 1</th>
            <th>Periodo</th>
            <th>Estado</th>
            <th>Avance</th>
            <th>Observaciones</th>
            <th>Resoluciones</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($titulaciones as $tit)
        <tr>
            <td>{{ $tit->tema }}</td>
            <td>{{ $tit->estudiante }}</td>
            <td>{{ $tit->cedula_estudiante }}</td>
            <td>{{ $tit->director }}</td>
            <td>{{ $tit->cedula_director }}</td>
            <td>{{ $tit->asesor1 }}</td>
            <td>{{ $tit->cedula_asesor1 }}</td>
            <td>{{ $tit->periodo->periodo_academico?? '' }}</td>
            <td>{{ $tit->estado->nombre_estado ?? '' }}</td>
            <td>{{ $tit->avance }}%</td>
            <td>{{ $tit->observaciones }}</td>
            <td>
                @foreach($tit->resTemas as $resTema)
                    {{ $resTema->resolucion->numero_res ?? '' }} ({{ $resTema->resolucion->tipoResolucion->nombre_tipo_res ?? '' }})<br>
                @endforeach
            </td>
            <td>
                <a href="{{ route('titulaciones.edit', $tit->id_titulacion) }}">Editar</a>
                <form action="{{ route('titulaciones.destroy', $tit->id_titulacion) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Está seguro de eliminar esta titulación?')">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection