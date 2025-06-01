{{-- filepath: resources/views/titulaciones/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<h2>Editar Titulación</h2>
<form action="{{ route('titulaciones.update', $titulacion->id_titulacion) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="tema" value="{{ $titulacion->tema }}" required>

    <label>Estudiante:</label>
    <input type="text" value="{{ $titulacion->estudiantePersona->nombres ?? '' }}" class="form-control" readonly>
    <input type="text" name="cedula_estudiante" value="{{ $titulacion->cedula_estudiante }}" class="form-control" required>

    <label>Director:</label>
    <input type="text" value="{{ $titulacion->directorPersona->nombres ?? '' }}" class="form-control" readonly>
    <input type="text" name="cedula_director" value="{{ $titulacion->cedula_director }}" class="form-control" required>

    <label>Asesor 1:</label>
    <input type="text" value="{{ $titulacion->asesor1Persona->nombres ?? '' }}" class="form-control" readonly>
    <input type="text" name="cedula_asesor1" value="{{ $titulacion->cedula_asesor1 }}" class="form-control" required>

    <select name="periodo_id" required>
        @foreach($periodos as $periodo)
            <option value="{{ $periodo->id_periodo }}" @if($titulacion->periodo_id == $periodo->id_periodo) selected @endif>{{ $periodo->periodo_academico }}</option>
        @endforeach
    </select>
    <select name="estado_id" required>
        @foreach($estados as $estado)
            <option value="{{ $estado->id_estado }}" @if($titulacion->estado_id == $estado->id_estado) selected @endif>{{ $estado->nombre_estado }}</option>
        @endforeach
    </select>
    <input type="number" name="avance" value="{{ $titulacion->avance }}" min="0" max="100" required>
    <textarea name="observaciones">{{ $titulacion->observaciones }}</textarea>
    <button type="submit">Actualizar</button>
</form>
@endsection