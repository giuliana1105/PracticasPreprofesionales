{{-- filepath: resources/views/titulaciones/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<h2>Editar Titulación</h2>
<form action="{{ route('titulaciones.update', $titulacion->id_titulacion) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" name="tema" value="{{ $titulacion->tema }}" required>
    <input type="text" name="estudiante" value="{{ $titulacion->estudiante }}" required>
    <input type="text" name="cedula_estudiante" value="{{ $titulacion->cedula_estudiante }}" required>
    <input type="text" name="director" value="{{ $titulacion->director }}" required>
    <input type="text" name="cedula_director" value="{{ $titulacion->cedula_director }}" required>
    <input type="text" name="asesor1" value="{{ $titulacion->asesor1 }}" required>
    <input type="text" name="cedula_asesor1" value="{{ $titulacion->cedula_asesor1 }}" required>
    <select name="periodo_id" required>
        @foreach($periodos as $periodo)
            <option value="{{ $periodo->id_periodo }}" @if($titulacion->periodo_id == $periodo->id_periodo) selected @endif>{{ $periodo->nombre }}</option>
        @endforeach
    </select>
    <select name="estado_id" required>
        @foreach($estados as $estado)
            <option value="{{ $estado->id_estado }}" @if($titulacion->estado_id == $estado->id_estado) selected @endif>{{ $estado->nombre }}</option>
        @endforeach
    </select>
    <input type="number" name="avance" value="{{ $titulacion->avance }}" min="0" max="100" required>
    <textarea name="observaciones">{{ $titulacion->observaciones }}</textarea>
    <button type="submit">Actualizar</button>
</form>
@endsection