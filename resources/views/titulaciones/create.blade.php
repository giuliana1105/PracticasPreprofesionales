{{-- filepath: resources/views/titulaciones/create.blade.php --}}
@extends('layouts.app')

@section('content')
<h2>Crear Titulación Manualmente</h2>
<form action="{{ route('titulaciones.store') }}" method="POST">
    @csrf
    <input type="text" name="tema" placeholder="Tema" required>
    <input type="text" name="estudiante" placeholder="Estudiante" required>
    <input type="text" name="cedula_estudiante" placeholder="Cédula Estudiante" required>
    <input type="text" name="director" placeholder="Director" required>
    <input type="text" name="cedula_director" placeholder="Cédula Director" required>
    <input type="text" name="asesor1" placeholder="Asesor 1" required>
    <input type="text" name="cedula_asesor1" placeholder="Cédula Asesor 1" required>
    <select name="periodo_id" required>
        <option value="">Seleccione Periodo</option>
        @foreach($periodos as $periodo)
            <option value="{{ $periodo->id_periodo }}">{{ $periodo->periodo_academico }}</option>
        @endforeach
    </select>
    <select name="estado_id" required>
        <option value="">Seleccione Estado</option>
        @foreach($estados as $estado)
            <option value="{{ $estado->id_estado }}">{{ $estado->nombre_estado }}</option>
        @endforeach
    </select>
    <input type="number" name="avance" placeholder="Avance (%)" min="0" max="100" required>
    <textarea name="observaciones" placeholder="Observaciones (opcional)"></textarea>
    <label>Resoluciones relacionadas:</label>
    <label>Resoluciones seleccionadas:</label>
<ul>
    @foreach($resolucionesSeleccionadas as $res)
        <li>{{ $res->numero_res }} - {{ $res->tipoResolucion->nombre ?? '' }}</li>
    @endforeach
</ul>

   
    <button type="submit">Guardar</button>
</form>

<hr>
<h2>Importar Titulaciones por CSV</h2>
<form action="{{ route('titulaciones.importCsv') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file" accept=".csv" required>
    <button type="submit">Importar CSV</button>
</form>

@endsection